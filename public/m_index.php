<?php 


function get_client_ip() {
	
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
    
   
}

// if (false || strpos($_SERVER['REQUEST_URI'],"/adminxxx") !== false || get_client_ip() == "2.224.168.43" || get_client_ip() ==  "109.234.61.59" /* giovanni casa */ ||  get_client_ip() == "::1") { 

// 	require __DIR__.'/../bootstrap/autoload.php';
// 	$app = require_once __DIR__.'/../bootstrap/app.php';
// 	$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
// 	$response = $kernel->handle($request = Illuminate\Http\Request::capture());
// 	$response->send();
// 	$kernel->terminate($request, $response);

// } else {
	
	header('HTTP/1.1 503 Service Temporarily Unavailable');
	header('Status: 503 Service Temporarily Unavailable');
	header('Retry-After: 300');//300 seconds
   	header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Connection: close");
    header('Vary: User-Agent');
	
?>
<html>
	<head>
		<title>1207 Alberghi e Hotel della Riviera Romagnola </title>
		<meta name="description" content="Scegli tra 1207 strutture della Riviera Romagnola: prezzi economici, nuova ricerca avanzata per stelle, servizi, trattamenti, offerte bambini e parchi. ">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:200,400,700&amp;subset=latin" media="all">
		<style>
			body { font-family: Roboto; font-size: 16px; padding:100px 50px 50px; line-height: 1.5em; }
			.logo { background-color: #333;  position: fixed; top:0; left:0px; right:0; padding:20px 60px;width 100%; display: block; }
			h1 { line-height: 1.5em; }
		</style>
	</head>
	<body>
		
		<div class="logo">
			<img src="/images/logo.png" />
		</div>
		
		<h1>Servizio temporaneamente non disponibile per manutenzione</h1>
		<p>Tra pochi minuti il sito sarà di nuovo online.<br />
			Ci scusiamo per il disagio e ti invitiamo a riprovare tra qualche istante.</p>
			<p>Puoi seguirci su <a href="https://www.facebook.com/infoalberghi/">Facebook</a> o contattarci all'indirizzo <a href="mailto:info@info-alberghi.com">info@info-alberghi.com</a><br />
			
	</body>
</html>

<?php //} ?>
