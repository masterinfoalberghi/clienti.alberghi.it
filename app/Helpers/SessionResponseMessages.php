<?php

use Illuminate\Http\Request;

/**
 * Helper per salvare un array di messaggi in sessione e "appenderli" ad un redirect<br>
 * Laravel nativamente ha una funzionalità simile ma aveva limitazioni che non mi vanno bene<br>
 * http://laravel.com/docs/5.1/responses#view-responses
 * 
 * @author Luca Battarra
 */

class SessionResponseMessages
  {
  static $msgs = [];

  /**
   * Appende un messaggio utente nel contenitore in sessione
   * 
   * @param string $type Tipologia del messaggio: info|success|warning|error
   * @param string $text Testo del messaggio
   */
  static public function add($type, $text)
    {
    self::$msgs[] = ["type" => $type, "text" => $text];
    }

  static public function hasErrors()
    {
    foreach(self::$msgs as $msg)
      if($msg["type"] === 'error')
        return true;

    return false;      
    }

  /**
   * Effettua il redirect salvando prima l'array dei messaggi in sessione,<br>
   * qualcosa di simile a  http://laravel.com/docs/5.1/responses#view-responses
   *   
   * @param  string $destination   
   * @param  Request $request
   * @return RedirectResponse
   */
  static public function redirect($destination, Request $request)
    {
    Session::put("SessionResponseMessages", self::$msgs);

    /* 
     * http://laravel.com/docs/5.1/requests#old-input
     * se non si usa la validazione interna di laravel, è necessario salvare manualmente il vecchio "post"
     * in sessione, in modo da non perdere i valori inseriti dall'utente nella form
     *
     * NB questa cosa è da fare solo in caso di errori, perchè negli altri casi voglio che il dato venga preso dal model e quindi dal DB!
     */    
    if(self::hasErrors())
      $request->flash();
    
    return redirect($destination);
    }

  /**
   * Ritorna l'array dei messaggi estraendolo dalla sessione
   * 
   * @return array
   */
  static public function retrieve()
    {
    $messages = Session::get("SessionResponseMessages", []);  

    Session::forget('SessionResponseMessages');

    return $messages;
    }

  /**
   * Ritorna la classe css Bootstrap per mostrare il messagio del tipo indicato<br>
   * http://getbootstrap.com/components/#alerts
   * 
   * @param  string $type
   * @return string info|success|warning|danger
   */
  static public function typeToClass($type)
    {      
    switch($type)
      {
      default:
        $class = "";
        break;
      case "info":
        $class = "info";
        break;
      case "success":
        $class = "success";
        break;
      case "warning":
        $class = "warning";
        break;
      case "error":
        $class = "danger";
        break;
      }
    return $class;
    }

  }