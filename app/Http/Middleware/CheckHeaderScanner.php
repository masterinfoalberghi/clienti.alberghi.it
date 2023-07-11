<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CheckHeaderScanner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {    
        //$minutes = 5;
        $minutes = 30;
        $header_name = 'ACUNETIX_PRODUCT';
        //$header_name = 'Referer';



        if($request->headers->has($header_name))
          {
            
            
            if(Storage::disk('local')->exists('mail_scanner_inviata.txt'))
              {

              $mail_scanner_inviata = Storage::get('mail_scanner_inviata.txt');
              $inviata = Carbon::createFromFormat('Y-m-d H:i:s', $mail_scanner_inviata); 

              // se l'ho inviata più di $minutes minuti fa 
              // distruggo la sessine
              if($inviata->diffInMinutes(Carbon::now()) > $minutes)
                {
                Storage::disk('local')->delete('mail_scanner_inviata.txt');
                }
              }


            if(!Storage::disk('local')->exists('mail_scanner_inviata.txt'))
              {
              ///////////////////////////////
              // IMPLEMENTARE invio mail  //
              //////////////////////////////
              
              $body = 'Richiesta da '.$header_name.' - prossima mail tra '.$minutes.' minuti';

              Mail::send([], [], function ($message) use ($header_name, $body)
              {
                $message->from('richiesta@info-alberghi.com');
                $message->returnPath('richiesta@info-alberghi.com')->sender('richiesta@info-alberghi.com')->to('luigi@info-alberghi.com');   
                $message->subject('ATTENZIONE: SCAN da '.$header_name. ' IN CORSO !!!');
                $message->setBody($body, 'text/html');
                $message->bcc('giovanni@info-alberghi.com');
              });

              Storage::disk('local')->put('mail_scanner_inviata.txt', Carbon::now()->toDateTimeString());
              }

            return response('Unauthorized.', 401);
          }
        
        return $next($request);
    }
}
