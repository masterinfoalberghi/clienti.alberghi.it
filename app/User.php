<?php

namespace App;

use Carbon\Carbon;
use SessionResponseMessages;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class User extends  Authenticatable
  {

   use Notifiable;



  // gg. dopo i quali viene inviaa una mail se l'hotel non ha acceduto il backend
  private $delay_days;

  /**
   * The database table used by the model.
   *
   * @var string
   */
  protected $table = 'users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'email', 'password'];

  /**
   * The attributes excluded from the model's JSON form.
   *
   * @var array
   */
  protected $hidden = ['password', 'remember_token'];




  public function __construct(array $attributes = [])
  {
      $this->delay_days = 20;
      parent::__construct($attributes);
  }



   /**
   * [getDates You may customize which fields are automatically mutated to instances of Carbon by overriding the getDates method]
   */
  public function getDates()
    {
    return ['created_at', 'updated_at', 'access_admin_at'];
    }



  /**
   * L'utente ha come ruolo uno di quelli passati?<br>
   * è una versione semplificata di questo:<br>
   * https://gist.github.com/drawmyattention/8cb599ee5dc0af5f4246
   *
   * @param  string|array  $roles Il (stringa) o i (array di stringhe) ruoli da verificare
   * @return boolean
   */
  public function hasRole($roles)
    {

    if(is_array($roles))
      $roles_arr = $roles;
    else
      $roles_arr = [$roles];

    foreach($roles_arr as $role_neded)
      {
      if(strtolower($role_neded) === strtolower($this->role))
        return true;
      }

    return false;
    }

  public function getUserName()
    {
    if($this->hasRole('hotel'))
      return $this->hotel->nome;

    else
      if ($this->name != "")
        return $this->name;
      else
        return $this->email;
      
    }


  public function hotel()
    {
    return $this->hasOne('App\Hotel', 'id', 'hotel_id');
    }


  /**
   * [clienti_come_commerciale un utente (commerciale) ha molti clienti (come commerciale)]
   * @return [type] [description]
   */
  public function clienti_come_commerciale()
    {
    return $this->hasMany('App\Hotel','commerciale_id','id');
    }

    public static function  getAcceptance (){
        
        $lastText = DB::table("tblReleaseTxt")->orderBy("id", "DESC")->first();
        $release  = DB::table("tblAcceptanceRelease")
            ->where("user_id", Auth::user()->id)
            ->where("release_id", $lastText->id)
                ->first();

        return [$lastText, $release];

    }

  public function accessLogs()
    {
        return $this->hasMany('App\UserAccessLog', 'id', 'user_id');
    }

  public function getHotelName()
    {
    if($this->hotel)
      return $this->hotel->getIdNome();

    return "-";
    }

  public function setUiEditingHotelId($id)
    {
    \Session::put("ui_editing_hotel_id", (int)$id);
    }

  public function getUiEditingHotelId()
    {
    return \Session::get("ui_editing_hotel_id", 0);
    }

  public function setUiEditingHotel($string)
    {
    \Session::put("ui_editing_hotel", $string);
    }

  public function getUiEditingHotel()
    {
    return \Session::get("ui_editing_hotel");
    }

    public function setUiEditingHotelPriceList($string)
    {
    \Session::put("ui_editing_hotel_pricelist", $string);
    }

  public function getUiEditingHotelPriceList()
    {
    return \Session::get("ui_editing_hotel_pricelist");
    }

    

  public function setCommercialeUiEditingHotelId($id)
    {
    \Session::put("commerciale_ui_editing_hotel_id", (int)$id);
    }

  public function getCommercialeUiEditingHotelId()
    {
    return \Session::get("commerciale_ui_editing_hotel_id", 0);
    }




  public function sendPasswordResetNotification($token)
  {
      //dd('invio la mail con il reset token'.$token. ' e utente '.$this->email);
      $to = $this->getEmailForPasswordReset();
      $oggetto = 'Info Alberghi, recupero password';
      $titolo_email = 'Procedura di recupero password';
      $username = $this->username;
      $name = $this->name;

      if ($name != "")
        $nome_cliente = $name . " (".$username.")";
      else
        $nome_cliente = $username;

      Mail::send('emails.password', compact('token','nome_cliente','titolo_email','username'), function ($message) use ($to, $oggetto) 
          {
            $message->from('no-reply@info-alberghi.com');
            $message->to($to);
            $message->subject($oggetto);      
          });
     


  }


  public static function byUsername($username ="")
    {
    return static::where('username',$username)->firstOrFail();
    }


  public function scopeWithRole($query, $role)
     {
         return $query->where('role', $role);
     }



  public function scopeAccessWithDelay($query)
    {
    $now_time  = Carbon::now()->toDateTimeString(); 
    return $query->whereRaw("DATEDIFF('".$now_time."', access_admin_at ) > ". $this->delay_days);
  
    }

  

    public function destroyMe()
      {
        //? Se l'account ha un hotel e appratiene ad un gruppo, LO TOLGO DA QUEL GRUPPO
        if (!is_null($this->hotel)) {
          if ($this->hotel->gruppo_id > 0) {
            $this->hotel->gruppo_id = 0;
            $this->hotel->save();
          } 
        }

        $this->delete();
      }
    
  

}



