<?php
namespace App\Http\Controllers;

/*
  NOTE:
  - la tabella tblMailScheda in cui si scrivono le mail mandate dal modulo, verrà cmq gestita tramite una model MailScheda in relazione 1-n con la Cliente
  in modo da sfruttare Eloquent; inoltre non si importeranno i dati vecchi nella stessa tabella (??)
  Quindi la creiamo con una migration ??
*/

use App\Coupon;
use App\CouponAttivazione;
use App\CouponGenerato;
use App\Hotel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use App\Http\Requests\RichiediCouponRequest;
use App\UtenteCouponGenerato;
use App\Utility;
use App\localita;
use App\utente;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Request;
use TCPDF;

class MYPDF extends TCPDF {

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $text = "Info Alberghi Srl - Via Gambalunga, 81/A - 47921 Rimini (RN) - T. 0541-29187 F. 0541-29187 - P. Iva 03479440400";
        $this->Cell(0, 10, $text, 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
    
}

class CouponController extends Controller
  {
  
  
   private function ToEmail ($to) {
	  	
	  	
	  	
	  	if (is_array($to)):
	  		  	
			$to= join(",", $to);
			$$to = str_replace(" ", "" , $to);
			$$to = explode(",", $to);
			
			// Elimino le email doppie
			$to_new = array();
			foreach($to as $single) {
				
				if (!in_array($single, $to_new)) {
					array_push($to_new, $single);
				}
				
			}
		else:
			
			$to = str_replace(" ", "" , $to);
			$to = explode(",", $to);
			$to_new = $to;
			
		endif;
		return $to_new; 
	  
  }


    private function _set_pdf_attributes(&$pdf,$nome_cliente)
      {
	      
	      
        // set document information
        $pdf->SetCreator("info-alberghi.com");
        $pdf->SetAuthor('Info Alberghi');
        $pdf->SetTitle($nome_cliente);
        $pdf->SetSubject($nome_cliente . '- Coupon');
        $pdf->SetKeywords('Coupon, '.$nome_cliente. ', Info Alberghi');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH,' ',PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_HEADER));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        //set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        //set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        //set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // ---------------------------------------------------------

        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 9, '', true);

        $pdf->setImageScale(3.779528);
        //$pdf->setImageScale(0.47);
        $pdf->setCellHeightRatio(1.25);
      }



    private function _set_content_pdf($coupon, $codice, $email_utente)
      {

        $nome = $coupon->cliente->nome;
        $indirizzo = $coupon->cliente->indirizzo;
        $cap = $coupon->cliente->cap;
        $localita = $coupon->cliente->localita->nome;
        $prov = $coupon->cliente->localita->prov;
        $tel = $coupon->cliente->telefoni_mobile_call;
        $email_cliente = $coupon->cliente->email;
        $link = $coupon->cliente->testo_link != '' ? Utility::stripProtocol($coupon->cliente->testo_link) : Utility::stripProtocol($coupon->cliente->link);
        $valore = $coupon->valore;
        $referente = strtoupper($coupon->referente);
        $durata_min = $coupon->durata_min;
        $adulti_min = $coupon->adulti_min;
        $dal = Utility::getLocalDate($coupon->periodo_dal, '%d/%m/%y');
        $al = Utility::getLocalDate($coupon->periodo_al, '%d/%m/%y');


        $html = "";

// Set some content to print
$html = $html . <<<EOD
<style>
table {
	background: #FFFFFF;
}
table.dot
{
  border: 1px dashed #000;
  position:relative;
 
}

td.second_row
{
  padding-left:250px;
}


img#to_cut
{
  position:absolute;
  top:400px;
  left:400px;
}


</style>

<table width="100%" cellspacing="50" cellpadding="50" class="dot"  bgcolor="#FFFFFF">
<tr>
  <td align="center">
    <table width="90%" border="0">
      <tr>
        <td align="left" width="70%">
          <table>
          <tr>
            <td>
              <span style="font-size: 50px;"><b>$nome</b></span>
            </td>
          </tr>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td>
              <span style="">$indirizzo<br>
              $cap - $localita ($prov) <br>
              Tel. $tel<br>
              $email_cliente<br>
              $link<br>
              </span>
            </td>
          </tr>         
          </table>
        </td>
        <td class="second_row" align="left" width="40%">
          <table>
          <tr><td>&nbsp;</td></tr>
          <tr>
            <td align="center">
              <br>
              <span style="font-size: 50px; color:#862011">
                <b>Risparmia subito!</b>
              </span>
            </td>
          </tr>
          <tr>
            <td align="center">
              <span style="font-size: 100px; color:#862011">
                 &euro; <b>$valore</b>
              </span>
            </td>
          </tr>     
          </table>
        </td>
      </tr>
      <tr>
        <td colspan="2" align="center">
          <span style="font-size: 40px;"><b>Buono Sconto n. $codice per $email_utente</b></span>
        </td>
      </tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="left">
          <span style="">TERMINI E CONDIZIONI D'USO E VALIDITA' DEL "BUONO-SCONTO – COUPON":</span>
          <span style="font-size: 30px;"><br>
          1. buono sconto - coupon n. $codice - rilasciato da $referente<br>
          2. del valore di &euro; $valore<br>
          3. valido presso $nome<br>
          4. ottenuto da $email_utente<br>
          5. da scontare sul prezzo di una prenotazione, secondo la disponibilit&agrave;, di un soggiorno minimo di $durata_min notti continuative, in favore di minimo $adulti_min adulti, per il periodo compreso tra il $dal e il $al.<br>
          6. Lo sconto sar&agrave; applicato ai prezzi praticati dalla struttura alberghiera nel periodo di soggiorno e pubblicati all’interno del sito $link<br>
          7. I "buoni sconto - coupon" sono cumulabili con  le altre  eventuali offerte presenti, nel dato periodo, all’interno del sito indicato al punto 6, ma NON sono cumulabili tra loro per una singola prenotazione, n&eacute; sono cumulabili con altre promozioni di "advance booking", quali, a titolo meramente esemplificativo, booking.com, expedia.com, groupon.com, tippest.it, etc.<br>
          8. Il "buono sconto - coupon" d&agrave; diritto ad uno sconto sul prezzo di acquisto di un soggiorno prenotato presso la struttura alberghiera offerente, alle presenti condizioni contrattuali.<br>
          9. L’utilizzo del "buono sconto - coupon" &egrave; condizionato alla preventiva prenotazione del soggiorno e, dunque, alla disponibilit&agrave; presso la relativa struttura alberghiera nel periodo di validit&agrave; del buono.<br>
          10. L’offerta &egrave; proposta dal singolo albergatore/esercente sotto la propria ed esclusiva responsabilit&agrave;. Info Alberghi srl non agisce in nome proprio, ma si limita a promuovere l’offerta ed a raccogliere le adesioni in nome e per conto del predetto, senza impegnarsi in alcun modo nei confronti dell’utente/cliente, n&eacute; fornire od offrire a questi alcun tipo di servizio.<br>
          11. La richiesta di emissione del "buono sconto - coupon" comporta l'accettazione da parte dell'utente delle presenti condizioni generali.<br>
          12. Il "buono sconto - coupon" &egrave; utilizzabile una sola volta e ha un limitato periodo di validit&agrave; entro il quale dovr&agrave; essere presentato per beneficiare dello sconto.<br>
          13. Il "buono sconto - coupon" non pu&ograve; essere cambiato in denaro contante equivalente, n&eacute; pu&ograve; essere utilizzato per il costo di altre prenotazioni o altre differenti tariffe.<br>
          14. Privacy – consenso al trattamento dei dati personali - Informativa ex art. 13 D.Lgs. 30 giugno 2003 n. 196<br>
          </span>
        </td>
      </tr>
      <tr><td>&nbsp;</td></tr>
      <tr><td>&nbsp;</td></tr>
      <tr>
        <td colspan="2" align="center">
          <span style="font-size: 40px; text-decoration:underline;"><b>Contatta subito la struttura per verificare la disponibilit&agrave; $tel</b></span>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
EOD;


        return $html;

      }



  
    private function _crea_pdf($coupon, $codice, $email_utente)
      {

        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $nome_cliente = $coupon->cliente->nome;

        $this->_set_pdf_attributes($pdf,$nome_cliente);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
			
        $html = $this->_set_content_pdf($coupon, $codice, $email_utente);

        $img = file_get_contents(public_path().'/images/coupon/cut.jpg');
        $pdf->Image('@'.$img,10,30);


        //$pdf->Image(asset('images/coupon/cut.jpg'),10,30);
        
        // output the HTML content
        $pdf->writeHTML($html, true, true, true, false, '');

        // ---------------------------------------------------------

        $filename = storage_path() . "/coupon/Info_Alberghi_Coupon_".$coupon->cliente->id."_".$coupon->id.".pdf";

        //$pdf->output($filename, 'I');
        
        $pdf->output($filename, 'F');

        return $filename;
      } 


    /*
    E' pubblica perché la utilizzo anche da admin per mandare all'albergatore un esempio di pdf creato
    */  
    public function crea_pdf_cliente($coupon, $codice, $email_utente)
      {

        $codice = '****' . substr($codice, 4);

        // create new PDF document
        $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $nome_cliente = $coupon->cliente->nome;

        $this->_set_pdf_attributes($pdf,$nome_cliente);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();

        $html = $this->_set_content_pdf($coupon, $codice, $email_utente);

        $img = file_get_contents(public_path().'/images/coupon/cut.jpg');
        $pdf->Image('@'.$img,10,30);

        
        //$pdf->Image(asset('images/coupon/cut.jpg'),10,30);
        
        // output the HTML content
        $pdf->writeHTML($html, true, true, true, false, '');

        // ---------------------------------------------------------

        $filename = storage_path() . "/coupon/Coupon_Cliente_".$coupon->cliente->id."_".$coupon->id.".pdf";

        //$pdf->output($filename, 'I');
        
        $pdf->output($filename, 'F');

        return $filename;
      } 




  /**
   * [_gia_attivato_con_codice gestisce il caso in cui il coupon sia già stato attivato ed esista quindi già un codice asseganto]
   * @param  [type] $codice [description]
   * @return [type]         [description]
   */
  private function _gia_attivato_con_codice($codice, $coupon, $email_utente, &$content_h1 = "", &$content_body = "")
    {
      
      $attivazioneCoupon = $coupon->attivazioniConMail($email_utente)->first();

      is_null($attivazioneCoupon) ? $data_attivazione = " " : $data_attivazione = Utility::getLocalDate($attivazioneCoupon->data_attivazione, '%d/%m/%y %H:%M');     

      // coupon già attivato con codice $codice
      $content_h1 = Lang::get('hotel.coupon_gia_attivato');
      $content_body = Lang::get('hotel.coupon_gia_attivato_p1') . $data_attivazione . Lang::get('hotel.coupon_gia_attivato_p2') . $email_utente;
      
      $filename = $this->_crea_pdf($coupon, $codice, $email_utente);

      $this->_invia_coupon_utente($filename, $coupon, $email_utente);

    }




    /**
     * [_invia_coupon_utente invia la mail all'utente con il coupon]
     * @param  [type] $filename     [description]
     * @param  [type] $coupon       [description]
     * @param  [type] $email_utente [description]
     * @return [type]               [description]
     */
    private function _invia_coupon_utente($filename, $coupon, $email_utente)
      {
        $nome_cliente = $coupon->cliente->nome;
        $mobile_cliente = $coupon->cliente->telefoni_mobile_call;
        $mail_cliente = $coupon->cliente->email;
		
		$email_utente = Self::ToEmail($email_utente);

        Utility::swapToSendGrid();
		
        Mail::send(['html' => 'emails.invio_coupon_utente'], compact('nome_cliente','mobile_cliente','mail_cliente','email_utente'), function ($message) use ($email_utente, $filename) 
          {
          $message->from('coupon@info-alberghi.com', 'Info Alberghi Coupon');
          $message->to($email_utente)->subject('Ecco il tuo coupon allegato');
          $message->attach($filename, ['mime' => 'application/pdf']);
          });

        return;
      }



    private function _invia_coupon_cliente($filename, $coupon, $email_utente)
      {

        $mail_cliente = $coupon->cliente->email;
        $nome_cliente = $coupon->cliente->nome;
		
		$mail_cliente = Self::ToEmail($mail_cliente);
		    
        Utility::swapToSendGrid();

        Mail::send(['html' => 'emails.invio_coupon_cliente'], compact('nome_cliente'), function ($message) use ($mail_cliente, $filename, $email_utente) 
          {
          $message->from('coupon@info-alberghi.com', 'Info Alberghi Coupon');
          $message->replyTo($email_utente);
          $message->to($mail_cliente)->subject('Info-Alberghi: Appena scaricato un coupon');
          $message->attach($filename, ['mime' => 'application/pdf']);
          });

        return;

      }



  public function invia_coupon_esempio_cliente($filename, $coupon, $codice, $email_utente)
    {

      $mail_cliente = $coupon->cliente->email;
      $nome_cliente = $coupon->cliente->nome;
      
      $codice = '****' . substr($codice, 4);

      $link = $coupon->cliente->testo_link != '' ? Utility::stripProtocol($coupon->cliente->testo_link) : Utility::stripProtocol($coupon->cliente->link);
	  
	  $mail_cliente = Self::ToEmail($mail_cliente);

      Utility::swapToSendGrid();
	  
      Mail::send(['html' => 'emails.invio_coupon_esempio_cliente'], compact('nome_cliente','coupon','codice','email_utente','link'), function ($message) use ($mail_cliente, $filename) 
        {
        $message->from('coupon@info-alberghi.com', 'Info Alberghi Coupon');
        $message->to($mail_cliente)->subject('Info-Alberghi: Conferma creazione coupon');
        $message->attach($filename, ['mime' => 'application/pdf']);
        });

      return;

    }



  
  /**
   * [_existsUniqueID ritorna CouponAttivazione se esiste già un coupon con questo link di attivazione, false altrimenti]
   * @param  [type] $uniqueID [description]
   * @return [type]           [description]
   */
  private function _existsUniqueID($uniqueID) 
    {
    return (CouponAttivazione::findByUniqueID($uniqueID)->get()->count() > 0) ? CouponAttivazione::findByUniqueID($uniqueID)->get()->first() : false;
    }
  

    /**
     * [_crea_link_attivazione genera il link inviato via mail con cui si attiva il coupon scaricato]
     * @param  [string] $email [email inserita per scaricare il coupon, a cui arriva la mail con il link di attivazione]
     * @return [string]        [description]
     */
  private function _crea_link_attivazione($email)
    {
      /**
       * The chance of collision is small, but I recommend checking your database first before sending it out (using UNIQUE constraints is an easy way).
       */
    do $generatedKey = sha1(mt_rand(10000, 99999) . time() . $email);
    while ($this->_existsUniqueID($generatedKey) != false);
    
    return $generatedKey;
    }



    /**
     * [_genera_codice_controllo genera il codice univoco stampato nel pdf del coupon]
     * ATTENZIONE: utilizzo una sottostringa ddell'hash, QUINDI POTREI AVERE DELLE COLLISIONI  
     * Quamdo genero il codice controllo che non sia stato asseganto
     * 
     * @param  Coupon $coupon [description]
     * @param  [type] $email  [description]
     * @return [type]         [description]
     */
  private function _genera_codice_controllo(Coupon $coupon, $email)
    {
      do 
        {
          $codice = time(). '_'.$coupon->id.'_'.$coupon->hotel_id.'_'.$coupon->utilizzati.'_'.$email;
          $codice_criptato = sha1($codice);
          $codice_coupon =   strtoupper(substr($codice_criptato,0,8));
        } while (CouponGenerato::findByCodice($codice_coupon)->get()->count() > 0); 

      return $codice_coupon;
    }
  


  public function visualizzaFormCoupon($coupon_id = 0, $hotel_id = 0) 
    {
    $coupon = Coupon::with('cliente')->findOrFail($coupon_id);
    return View::make('hotel.form_coupon', compact('coupon'));
    }
  


    /**
     * [scaricaCoupon metodo richiamato come action del popup form in cui inserisco la mail per scaricare il coupon]
     * @param  RichiediCouponRequest $request [description]
     * @return [type]                         [description]
     */
  public function scaricaCoupon(RichiediCouponRequest $request) 
    {


    $locale = $this->getLocale();
    $seconds_to_wait = 10; 
    
    // innanzitutto verifico se esiste già , per questo hotel,
    // un coupon_generato ed associato a questa mail
    
    $coupon_id = $request->get('coupon_id');
    $hotel_id = $request->get('hotel_id');
    $email = $request->get('email_coupon');
    
    $redirect_url = "/back_hotel/$hotel_id";

    $coupon = Coupon::with(['generati.utente', 'attivazioni', 'cliente.localita'])->attivo()->find($coupon_id);
    
    // PER PRIMA COSA VERIFICO CHE IL COUPON SIA ONLINE (potrebbe essere già scaduto!!!)
    // E
    // CHE IL COUPON coupon_id sia effettivamente del cliente hotel_id

    
    if (is_null($coupon)) 
      {

      $msg_content = Lang::get('hotel.coupon_scarica_nuovo');
      return View::make('coupon.response_form_coupon_avviso', compact('msg_content','locale', 'seconds_to_wait','redirect_url'));

      } 
    elseif ($coupon->cliente->id != $hotel_id) 
      {
      
      $msg_content = Lang::get('hotel.coupon_scarica_corretto');
      return View::make('coupon.response_form_coupon_avviso', compact('msg_content','locale', 'seconds_to_wait','redirect_url'));

      }
    
    $gia_attivato = 0;
    
    foreach ($coupon->generati()->with('utente')->get()  as $generati) 
      {
        if (!is_null($generati->utente)) 
          {
            if ($generati->utente->email == $email) 
              {
              $gia_attivato = 1;
              //già attivato con codice $generati->codice;
              
              $content_body = "";
              $this->_gia_attivato_con_codice($generati->codice, $coupon, $generati->utente->email,$content_h1,$content_body);
              $msg_content = $content_body;
              return View::make('coupon.response_form_coupon_avviso', compact('msg_content','locale', 'seconds_to_wait','redirect_url'));

              }
          }
      }
    
    $gia_scaricato = 0;
    if (!$gia_attivato) 
      {
      
      //da attivare!!;
      
      // se non l'ho già attivato verifico se ho creato il link di attivazione
      foreach ($coupon->attivazioni as $attivati) 
        {
        if ($attivati->email == $email) 
          {
          $gia_scaricato = 1;
          $uniqueID = $attivati->uniqueID;
          //già scaricato con codice di attivazione $attivati->uniqueID";
          break;
          }
        }
      
      if (!$gia_scaricato) 
        {
        //da scaricare!!;
        
        // creo il link di attivazione
        $uniqueID = $this->_crea_link_attivazione($email);
        
        /**
         * Inserisco la riga in coupon_activation data_creazione = CURRENT_TIMESTAMP
         * che mi dice quando è stato creato il record, cioè quando hanno inserito la mail (la PRIMA VOLTA) nel popup per richiedere il coupon
         */
        
        $coupon->attivazioni()->save(new CouponAttivazione(['email' => $email, 'uniqueID' => $uniqueID]));
        }
      
      // creazione link attivazione
      
      // CREO LA MAIL CON IL LINK DI ATTIVAZIONE e la mando all'utente
      $nome = $coupon->cliente->nome;
      $email = Self::ToEmail($email);

      Utility::swapToSendGrid();
      
      Mail::send(['html' => 'emails.attivazione_coupon'], compact('uniqueID','nome', 'locale'), function ($message) use ($email) 
        {
        
        $message->from('coupon@info-alberghi.com', 'Info Alberghi Coupon');
        $message->to($email)->subject('Ottieni il tuo coupon ora!');
        });
      

      return View::make('coupon.response_form_coupon_link', compact('coupon','locale', 'seconds_to_wait','redirect_url'));
      
      } 

      

    } // end function scaricaCoupon
  
  

    /**
     * [attivazioneCoupon metodo richiamato quando clicco il link di attivazione dalla mail]
     * @param  integer $uniqueID [description]
     * @return [type]            [description]
     */
  public function attivazioneCoupon($uniqueID = 0) 
    {

    $locale = $this->getLocale();
    $redirect_url = '/';
    $seconds_to_wait = 10; 
    $attivazioneCoupon = $this->_existsUniqueID($uniqueID);
    
    if (!$attivazioneCoupon) 
      {
      
		// link sbagliato
		$content_h1 = Lang::get('hotel.coupon_attivazione_ko');
		$content_body = Lang::get('hotel.coupon_link_errato');;
		
		return View::make('coupon.attivazione_coupon', compact( 'content_h1', 'content_body','locale', 'seconds_to_wait','redirect_url'));
      
      } 
    else
      {

      $couponCollection = $attivazioneCoupon->coupon()->attivo()->disponibile()->fruibile()->get();

      // verifico che il coupon sia ancora online
      if ($couponCollection->count() == 0) 
        {

        // il copon da attivare è SCADUTO non è più online
        $content_h1 = Lang::get('hotel.coupon_attivazione_ko');
        $content_body = Lang::get('hotel.coupon_link_scaduto');
       
        return View::make('coupon.attivazione_coupon', compact('content_h1', 'content_body','locale', 'seconds_to_wait','redirect_url'));

        } 
      else
        {

        $coupon = $couponCollection->first();

        // il coupon è di questo hotel quindi dopo che l'ho attivato ritorno alla scheda di questo hotel 
        $redirect_url = "/back_hotel/$coupon->hotel_id";

        $email_utente = $attivazioneCoupon->email;
        $codice = "";
        if ($attivazioneCoupon->attivato == 1) 
          {
            // se è stato attivato lo trovo tra i coupon generati associato all'email utente
            

            foreach ($coupon->generati()->with('utente')->get() as $couponGenerato) 
              {
              if ($couponGenerato->utente->email == $email_utente) 
                {
                  $codice = $couponGenerato->codice;
                  break;
                }  
              }


            if ($codice != "") 
              {
                $content_h1 = "";
                $content_body = "";
                $this->_gia_attivato_con_codice($codice, $coupon, $email_utente,$content_h1,$content_body);
                
              }
           
          } 
        else 
          {

            DB::transaction(function() use (&$attivazioneCoupon, &$coupon, $email_utente, &$codice)
            {
                // coupon da attivare
                
                //aggiorno la riga di attivazione 
                $attivazioneCoupon->aggiornaRigaAttivazione();
                $attivazioneCoupon->save();

                // genera il codice di controllo da assegnare al coupon (e da stampare nel pdf)

                $codice = $this->_genera_codice_controllo($coupon, $email_utente);


                // creo le istanze di CouponGenerato e UtenteCouponGenerato

                $couponGenerato = $coupon->generati()->save(new CouponGenerato(['codice' => $codice]));

                $couponGenerato->utente()->save(new UtenteCouponGenerato(['email' => $email_utente]));


                // aggiorno i coupon utilizzati
                $coupon->triggerUtilizzati();


            });

            $content_h1 = Lang::get('hotel.coupon_attivazione_ok');
            $content_body = Lang::get('hotel.coupon_attivazione_mail') . $email_utente;  
        

            $filename = $this->_crea_pdf($coupon, $codice, $email_utente);

            // invio all'utente
            $this->_invia_coupon_utente($filename, $coupon, $email_utente);



            $filename = $this->crea_pdf_cliente($coupon, $codice, $email_utente);

            // invio all'albergatore
            $this->_invia_coupon_cliente($filename, $coupon, $email_utente);


          }

          return View::make('coupon.attivazione_coupon', compact('content_h1', 'content_body','locale', 'seconds_to_wait','redirect_url'));
            
        } // coupon ancora attivo/online
        
      } // link sbagliato


    } // end function attivazioneCoupon
  
  
  }
