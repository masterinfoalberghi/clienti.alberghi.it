<?php

namespace App\Console\Commands;

use App\CmsPagina;
use Illuminate\Console\Command;

class newUrl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'duplicate';

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
        
        /**
	     * ramo 1 
	     */
		$loca = ["rimini", "riccione", "cattolica", "misano-adriatico", "gabicce","bellaria", "cervia", "milano-marittima", "cesenatico", "lidi-ravennati", "riviera-romagnola"];
        
        $lang = ["ing/[1]-last-minute-deals/", "fr/[1]-derniere-minute/", "de/[1]-last-minute-angebote/"];
		$sorg = "offerte-last-minute-[1]/";
        $mesi = [];
        $mesi["maggio"] = [ "may", "mai", "mai"];
        $mesi["giugno"] = [ "june", "juin", "juni"];
        $mesi["luglio"] = [ "july", "juillet", "juli"];
        $mesi["agosto"] = [ "august", "aout", "august"];
        $mesi["settembre"] = ["september", "septembre", "september"];
        
        foreach ($mesi as $k => $m):
        	
        	foreach($loca as $lo):
        	
	        	$origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
	        	$pagina = CmsPagina::where("uri", $origine)->first();
						
				if ($pagina):
					
					if ($lo == "gabicce")
						$lo = "gabicce-mare";
					
		        	$t = 0;
		        	foreach ($lang as $l):
		        		
		        		$uri =  str_replace("[1]" , $m[$t] , $l) . $lo . ".php";
		        		
		        		if ($m[$t] == "august" && $t == 0):
		        			
		        			echo "--> $uri  non processato";
							echo PHP_EOL; 
		        		 
		        		else:
			        		
			        			$paginaTest = CmsPagina::where("uri", $uri)->first();
			        			
			        			if (!$paginaTest):
			        			
				        			$nuovaPagina = $pagina->replicate();
									$nuovaPagina->attiva = 0;
									$nuovaPagina->uri = $uri;
									$nuovaPagina->save();				
									
									echo "pagina $uri creata";
									echo PHP_EOL; 
									
								endif;
								
							
						endif;

		        		$t++;
		        		
		        	endforeach;
	        	
	        	else:
	        	
	        		echo "--> $origine non travata ";
	        		echo PHP_EOL; 
	        		
	        	endif;
        	
        	endforeach;
        
        endforeach;
        
        /**
	     * Rami 2
	     */
	   
	   $lang = ["fr/15-aout-offres/", "ted/15-august-angebote/"];  
	   $sorg = "offerte-ferragosto/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL; 
	    		
	    	endif;
	   
	   endforeach;
	   
	   /**
		* Ramo 3
		*/

	   $lang = ["fr/pension-complete/", "ted/vollpension/"];  
	   $sorg = "pensione-completa/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL; 
	    		
	    	endif;
	   
	   endforeach;

	   /**
		* Ramo 4
		*/

	   $lang = ["fr/demi-pension/", "ted/halbpension/"];  
	   $sorg = "mezza-pensione/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL; 
	    		
	    	endif;
	   
	   endforeach;

	   /**
		* Ramo 5
		*/

	   $lang = ["ing/only-room/", "fr/chambre-seule/","ted/nur-zimmer/"];  
	   $sorg = "solo-dormire/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL; 
	    		
	    	endif;
	   
	   endforeach;
	 
	   /**
		* Ramo 6
		*/

	   $lang = ["ing/accessible-hotels/", "fr/hotels-accessibles/", "ted/barrierefreie-hotels/"];  
	   $sorg = "hotel-disabili/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL;  
	    		
	    	endif;
	   
	   endforeach;
	   
	   /**
		* Ramo 7
		*/

	   $lang = ["ing/accessible-hotels/", "fr/hotels-accessibles/", "ted/barrierefreie-hotels/"];  
	   $sorg = "hotel-disabili/";
	    
	   foreach($loca as $lo):
	   	
	   		if ($lo == "gabicce")
				$lo = "gabicce-mare";
	   	
		   $origine = str_replace("[1]" , $k , $sorg) . $lo . ".php"; 
		   $pagina = CmsPagina::where("uri", $origine)->first();
		   
		   if ($pagina):
		   
			   foreach ($lang as $l):
					
					
					
					$uri = $l . $lo . ".php";
					$paginaTest = CmsPagina::where("uri", $uri)->first();
				        			
	    			if (!$paginaTest):
	    			
	        			$nuovaPagina = $pagina->replicate();
						$nuovaPagina->attiva = 0;
						$nuovaPagina->uri = $uri;
						$nuovaPagina->save();				
						
						echo "pagina $uri creata";
						echo PHP_EOL; 
						
					endif;
					
							   
			   endforeach;
		   
		   else:
	        	
	    		echo "--> $origine non travata ";
	    		echo PHP_EOL; 
	    		
	    	endif;
	   
	   endforeach;

	 
        
    }
}
