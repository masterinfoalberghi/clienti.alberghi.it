<?php

/**
 * Fornisce supporto per lavorare con le lingue
 * 
 * @author Luca Battarra
 */

class Langs
  {

  /**
   * Ritorna un array con i codici delle lingue
   * @return array
   */
  static public function getAll()
    {
    return ["it", "en", "fr", "de"];      
    }

  /**
   * Ritorna la URL dell'immagine con la bandiera della lingua richiesta
   * @param  string $code
   * @return string      
   */
  static public function getImage($code)
    {
    return Utility::assetsImage("icons/$code.png");
    }

  /**
   * Ritorna il nome esteso della lingua richeista
   * @param  string $code
   * @return string
   */
  static public function getName($code)
    {

    return [
      "it" => "Italiano",
      "en" => "English",
      "fr" => "Français",
      "de" => "Deutsche",
    ][$code];
    }

  }