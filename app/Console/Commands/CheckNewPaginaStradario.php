<?php

namespace App\Console\Commands;

use App\CmsPagina;
use App\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckNewPaginaStradario extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'stradario:check_new';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Cerca le strade che hanno almeno 5 hotel e SE NON ESISTE GIÀ crea la pagina corrispondete';


	const MS_RETURN_PATH = "richiesta@info-alberghi.com";


	public $email_mittente;
	public $nome_mittente;
	public $to;
	public $oggetto;



	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->email_mittente = "no-reply@info-alberghi.com";
		$this->nome_mittente = "Valentina";
		$this->to = "valentina@info-alberghi.com";
		$this->oggetto = "Nuove pagine stradario create";
		parent::__construct();
	}


	private function _checkIfStardaExistsAsPage($strada)
	{
		return CmsPagina::where('indirizzo_stradario', '!=', '')->where('indirizzo_stradario', '=', $strada->indirizzo)->where('localita_id_stradario', '=', $strada->id_localita)->where('macrolocalita_id_stradario', '=', $strada->id_macrolocalita)->count();
	}




	private function _sendMail($new_pages = [])
		{

		$oggetto = count($new_pages). ' '.$this->oggetto;

		Mail::send(
			'emails.nuove_pagine_stradario', 
			compact(
				'new_pages','oggetto'
			), 
			function ($message) {
				
				$message->from(Self::MS_RETURN_PATH, $this->nome_mittente);
				$message->returnPath(Self::MS_RETURN_PATH)->sender(Self::MS_RETURN_PATH)->to($this->to);
		
				if (isset($this->bcc) && $this->bcc != "")
					$message->bcc($this->bcc);
		
				$message->subject("[INFO ALBERGHI] Avviso! Nuove pagine stradario create");
				
			}
		);
		
		}



	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */

	public function handle()
	{

		$strade =  Utility::getStradeStradario();
		$new_pages = [];

		$this->info("------------------");
		foreach ($strade as $strada) {
			if ($this->_checkIfStardaExistsAsPage($strada) == 0) {
				Utility::insertStadaAsPage($new_pages, $strada);
				$this->info("$strada->indirizzo,$strada->localita DA INSERIRE");
			}
			else {
				$this->info("$strada->indirizzo,$strada->localita già presente");
			}
		}

		$this->info("------------------");
		if (count($new_pages)) 
			{
			DB::table('tblCmsPagine')->insert($new_pages);
			$this->_sendMail($new_pages);			
			}
		$this->info("Inserite ".count($new_pages) . " nuove pagine nello stradario");
		
		if (count($new_pages))	
			{
			$this->info("Inviata mail notifica !!");
			}
	}


}
