<?php

namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;

class SetOfferteFromVisibilita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu:visibilita';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	    
	    $arrayPagine = [];
		$arrayPagine[] = 'offerte-last-minute-maggio/';
		$arrayPagine[] = 'offerte-last-minute-giugno/';
		$arrayPagine[] = 'offerte-last-minute-luglio/';
		$arrayPagine[] = 'offerte-last-minute-agosto/';
		$arrayPagine[] = 'offerte-last-minute-settembre/';
		$arrayPagine[] = 'offerte-1-maggio/';
		$arrayPagine[] = 'offerte-2-giugno/';
		$arrayPagine[] = 'offerte-25-aprile/';
		$arrayPagine[] = 'offerte-ferragosto/';
		$arrayPagine[] = 'capodanno/';
		$arrayPagine[] = 'offerte-halloween/';
		$arrayPagine[] = 'offerte-san-valentino/';
		$arrayPagine[] = 'pasqua-riccione/pasqua.php';
		
		foreach( $arrayPagine as $pagine):
			
			$idsDB = DB::table('tblCmsPagine')->whereRaw("uri LIKE '%" . $pagine . "%'")->get();
			$idsArray = $idsDB->pluck("id")->toArray();
			
			$menu = DB::table('tblMenuTematico')->whereIn("cms_pagine_id", $idsArray)->get();
			
			foreach($menu as $m):
				
				//DB::raw('UPDATE tblMenuTematico SET type="offerte" WHERE id="'.$m->id.'"');
				echo 'UPDATE tblMenuTematico SET type="offerte" WHERE id="'.$m->id.'"' . PHP_EOL;
			
			endforeach;
			
		endforeach;
        
    }
}
