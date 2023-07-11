<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App;
use Auth;

abstract class AdminBaseController extends BaseController
  {

  use DispatchesJobs, ValidatesRequests;


  private $host = "";
  private $user = "";
  private $key = "";

  



    ////////////////////////////////////////////////////
    // I can call these functions in every controller //
    ////////////////////////////////////////////////////
    
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
    {
    $this->middleware('auth');
    $this->host = "https://api.sendgrid.com/api";
    $this->user = "infoalberghi.com";
    $this->key = "yKW7KCYz";
    }


  public function _make_url_sd($uri="bounces.count.json", $param="days=1")
      {
        $url =  $this->host.'/'.$uri.'?api_user='.$this->user.'&api_key='.$this->key.'&date=1&'.$param;

        return $url;
      }
  
    
    public function _get_call_sd($url)
      {
      	//open connection
			$ch = curl_init();

			//set the url, number of POST vars, POST data
			curl_setopt($ch,CURLOPT_URL, $url);
		
			//So that curl_exec returns the contents of the cURL; rather than echoing it
			curl_setopt($ch,CURLOPT_RETURNTRANSFER, true); 

			//execute post
      $result = curl_exec($ch);
      
			return $result;
      }


  /*
   * I metodi di questa classe sono disponibili per tutti i controller che la ereditano
   * quindi per tutti i controller della directory Controllers\Admin\
   */

  // METTO la casistica del commerciale nella funzione getHotelId 
  // se un commerciale copiasse un url a cui teoricamente non ha accesso (admin/listini-custom)
  // NON ACCEDERE COMUNQUE perchè il controllo viene fatto nella route!! 
  protected function getHotelId()
    {

    /*
     * Puoi accedere all'interfaccia di editing delle informazioni di un hotel solo se:
     * - fai login come hotel
     * - fai login come admin e impersonifichi un hotel
     * altrimenti non devi poter procedere!
     */

    $id = 0;
    if(Auth::user()->hasRole(["admin", "operatore"]))
      $id = Auth::user()->getUiEditingHotelId();
    elseif (Auth::user()->hasRole(["commerciale"]))
      $id = Auth::user()->getCommercialeUiEditingHotelId();
    else
      $id = Auth::user()->hotel_id;

    if(!$id)
      {
        abort(404);
      }
    else
      {
        return $id; 
      }


    }


    /**
     * [getLocale legge il locale della configurazione corrente eventualmente settato dal middleware Lang]
     * @return [type] [description]
     */
    protected function getLocale() 
      {
      return App::getLocale();
      }
  }
