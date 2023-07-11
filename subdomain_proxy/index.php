<?php

/**
 * Alcuni terzi livelli, sono dei proxy a delle url gestite da laravel
 * I terzi livelli sono:
 * fierarimini.info-alberghi.com
 * hotelperdisabili.info-alberghi.com
 *
 * Ad esempio:
 * richiedendo questa url del terzo livello
 * http://fierarimini.info-alberghi.com/campionato-indoor-tiro-arco.php
 *
 * questo proxy si preoccuperà di chiamare su laravel
 * http://www.info-alberghi.com/fierarimini/campionato-indoor-tiro-arco.php
 *
 * ne riporterà http status code e output
 *
 * NB se richiedo direttamente
 * http://www.info-alberghi.com/fierarimini/campionato-indoor-tiro-arco.php
 * deve tornare 404
 *
 * Laravel saprà che è questo script che lo chiama perchè passerò l'header
 * "IA-Subdomain-Proxy"
 * riteniamo questo approccio più flessibile e comodo rispetto ad un controllo sull'IP
 *
 * Attenzione, non rinominare questo script (vedi .htaccess)
 *
 * Attenzione, con questo sistema di proxy, la sessione non viene gestita
 * per intenderci: ogni F5 crea una nuova sessione
 *
 * @author Luca Battarra
 */

function header_404()
  {
  header("HTTP/1.0 404 Not Found");
  }


/*
 * Se c'è qualcosa di strano, torno l'header "sito in manutenzione"
 */
function header_503($custom_error_message = null)
  {

  // http://googlewebmastercentral.blogspot.it/2011/01/how-to-deal-with-planned-site-downtime.html
  header('HTTP/1.1 503 Service Temporarily Unavailable');

  // messaggio custom con eventuali dettagli sul perchè
  if($custom_error_message)
    header("IA-Proxy-Error: $custom_error_message");
  }


$request_uri = isset($_GET["url"]) ? $_GET["url"] : null;
$http_host = isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : null;


// Estraggo il terzo livello dall'host
$subdomain = null;
if($http_host)
  {
  $host = explode(".", $http_host);
  if(is_array($host) && count($host) === 3)
    $subdomain = $host[0];
  }


/*
 * su Laravel in tblCmsPagine, abbiamo il campo uri
 * bisogna presentarsi a cercare su quella pagina con esattamente il valore presente nel campo...
 * per quanto riguarda le "home" fiere e disabili i record hanno nel campo uri i valori
 * hotelperdisabili/index.php
 * fierarimini/index.php
 *
 * Nel proxy devo puntare su queste url in tutti questi casi:
 * - http://fierarimini.info-alberghi.com
 * - http://fierarimini.info-alberghi.com/
 * - http://fierarimini.info-alberghi.com/index.php
 *
 * qui faccio proprio questo, faccio puntare tutti questi casi su index.php
 */
if(!$request_uri || $request_uri === "/")
  {
  $request_uri = "index.php";
  }


if($subdomain)
  {
  $uri = "/$subdomain/$request_uri";

  $url = "http://www.info-alberghi.com".$uri;
  //$url = "http://192.168.2.37/infoalberghi/public".$uri;


  $ch = curl_init();    // initialize curl handle
  curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
  //curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable

  /*
   * Il sito, sulla base dello user agent, capisce se tornare la pagina mobile
   * o la pagina in formato desktop. È quindi importante in questo proxy
   * inolatrare al sito laravel lo user-agent con cui viene richiesto il proxy
   */
  if(isset($_SERVER["HTTP_USER_AGENT"]))
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);

  /*
   * ci può impiegare al massimo 1 secondo a connettersi
   * e 3 per completare la richiesta.
   * È importante che il limite non sia maggiore, altrimenti potrebbe andare giù tutto il web-server
   * se la richiesta che cerco di richiedere con curl non fosse raggiungibile
   */
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT, 1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 3);

  curl_setopt($ch, CURLOPT_HTTPHEADER, array("IA-Subdomain-Proxy: $subdomain"));

  $result = curl_exec($ch); // run the whole process

  if($errno = curl_errno($ch))
    {
    header_503("[CURL errno:$errno] ".curl_error($ch));
    }
  else
    {
    $http_status_code = (string)curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    $http_code_family = substr($http_status_code, 0, 1);

    if($http_code_family == "4")
      header_404();
    if($http_code_family == "5")
      header_503("La richiesta $url ha tornato HTTP_CODE=$http_code_family");

    /*
     * In alcuni casi, curl potrebbe semplicemente morire senza tornare errori
     * in quel caso la risposta sarebbe vuota, la gestisco con un 503
     */
    if($result)
      echo $result;
    else
      header_503("CURL ha inaspettatamente tornato una risposta vuota e nessun codice di errore");
    }
  }
else
  header_503("Impossibile recuperare il subdomain dall'host $http_host");
