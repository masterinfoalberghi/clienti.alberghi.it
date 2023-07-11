<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use File;
use App\Hotel;
use App\ImmagineGallery;
use App\SlotVetrina;
use App\library\ImageVersionHandler;

class ImageCreateVersions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:create_versions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
     
    
     
    public function __construct()
    {
        parent::__construct();
    }

    protected function listing()
    {

      $hotels = Hotel::select("id", "listing_img")->attivo()->get();

      $this->info("hotels estratti dal DB: ".count($hotels));

      $done = 0;
      $nt_done = 0;

      foreach($hotels as $hotel)
        {
	        
        $img = ImmagineGallery::where("hotel_id", $hotel->id)
          ->orderBy('position', 'asc')
          ->take(1)
          ->first();

        if(!empty($img->foto))
            {

			$file = public_path("images/gallery/360x200/".$img->foto);
            if(file_exists($file)) {
                try
                    {
					
                    $imagev = new ImageVersionHandler;

                    $uploaded_filename = File::name($file);

                    // mantengo il filename esattamente invariato
                    $imagev->setImageBasename($uploaded_filename);
                    $backup_path = config("app.storage_images_path") . "/original_images/listing";
                    $imagev->enableOriginalBackup($backup_path);
                    $imagev->loadOriginalFromPath($file);

                    foreach(ImmagineGallery::getImagesVersions() as $v)
                      {
                      $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);
                      }

                    $hotel->update(["listing_img" => $img->foto]);

                    $done++;

                    if(!($done % 50))
                        $this->info("immagini riprocessate: $done");

                    }
                  catch (\Exception $e)
                    {
                    $this->error($e->getMessage());
                    }
                }
                
            } else {
	            
	             $nt_done++;
	             $this->info("immagini non riprocessate: $nt_done (hotel id: ". $hotel->id ." / file: $file)");
	             
            }        
        
        }

      $this->info("immagini riprocessate: $done");

    }

    protected function vetrine()
    {
        /*
         * immagini vetrine
         */
        $slots = SlotVetrina::all();

        $this->info("slot vetrina estratti dal DB: ".count($slots));

        $done = 0;
		$nt_done = 0;
		
        foreach($slots as $slot)
            {

            if($slot->immagine)
                {
				
                $file = config("app.storage_images_path")  . "/vetrine/" . $slot->immagine;
  	            
				if(!file_exists($file)) {
                    $file = config("app.storage_images_path") . "/gallery/" . $slot->immagine;
				}
				
				if(!file_exists($file)) {
	            	$file = public_path("images/gallery/800x538/".$slot->immagine);
				}
				
				if(!file_exists($file)) {
	            	$file = public_path("images/vetrine/220x148/".$slot->immagine);
				}

                if(file_exists($file))
                    {
                    try
                        {
                        $done++;
						
						if(!($done % 50))
                            $this->info("immagini riprocessate: $done");
						
                        $imagev = new ImageVersionHandler;
                        $uploaded_filename = File::name($file);

                        // mantengo il filename esattamente invariato
                        $imagev->setImageBasename($uploaded_filename);

                        $imagev->loadOriginalFromPath($file);

                        foreach(SlotVetrina::getImagesVersions() as $v)
                          {
                          $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);
                          }
                        }
                      catch (\Exception $e)
                        {
                        $this->error($e->getMessage());
                        }
                    }
                } else {
	             $nt_done++;
	             $this->info("immagini non riprocessate: $nt_done (hotel id: ". $slot->hotel_id ." / file: $file)");
	            
				 }   
            }  

        $this->info("immagini riprocessate: $done");      
    }

    protected function gallery()
    {
        /*
         * immagini gallery
         */
		 
		$hotels = Hotel::select("id", "listing_img")->attivo()->get();
		 
        //$imgs = ImmagineGallery::all();
        //$imgs = ImmagineGallery::orderBy('hotel_id')->get();
        
        foreach($hotels as $hotel) {
        
	        $imgs = ImmagineGallery::where("hotel_id", $hotel->id)->orderBy('position', 'asc')->get();
	        $this->info("immagini gallery estratte dal DB per l'hotel (".$hotel->id."): ".count($imgs));
			
			$nt_done = 0;
	        $done = 0;
	
	        foreach($imgs as $img)
	            {
	
	            if($img->foto)
	                {
                        
                    

					if(!file_exists($file)) {
                        $file = config("app.storage_images_path")  . "/gallery/800x538/" . $img->foto;
					}
					
	                if(file_exists($file))
	                    {
	                    try
	                        {
	                        $done++;
	
	                        if(!($done % 500))
	                            $this->info("immagini riprocessate: $done");
	
	                        $imagev = new ImageVersionHandler;
	
	                        $uploaded_filename = File::name($file);
	
	                        // mantengo il filename esattamente invariato
	                        $imagev->setImageBasename($uploaded_filename);
	                        $imagev->loadOriginalFromPath($file);
	
	                        foreach(ImmagineGallery::getImagesVersions()["220x148"] as $v)
	                          {
	                          $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);
	                          }
	                        }
	                      catch (\Exception $e)
	                        {
	                        $this->error($e->getMessage());
	                        }
	                    }
	                } else {
		                 $nt_done++;
						 $this->info("immagini non riprocessate: $nt_done (hotel id: ". $hotel->id ." / file: $file)");
	                }
	            }
            
            }

			$this->info("immagini riprocessate: $done");  
			   
    }
	
	const LISTING_IMG_PATH = "/images/listing/";
	const GALLERY_IMG_PATH = "images/gallery/";
	
	protected function vecchie_img()
    {
     	$hotels = Hotel::select("id", "listing_img")->attivo()->get();
		$this->info("hotels estratti dal DB: ".count($hotels));
		$done = 0;
		$nt_done = 0;
		foreach($hotels as $hotel){
			$img = ImmagineGallery::where("hotel_id", $hotel->id)
			->orderBy('position', 'asc')
			->take(1)
			->first();
			if(!empty($img->foto)){
				$file = public_path(self::LISTING_IMG_PATH . "/360x200/".$img->foto);
				if(file_exists($file)){
					try{
						$imagev = new ImageVersionHandler;
						$uploaded_filename = File::name($file);
						$imagev->setImageBasename($uploaded_filename);
						$imagev->loadOriginalFromPath($file);
						$imagev->process("crop", public_path(self::LISTING_IMG_PATH."/220x148") , 220, 148); 
						$done++;
						$this->info("immagini riprocessate: $done");
					} catch (\Exception $e){
						$this->error($e->getMessage());
					}
				}
			} else {
				$nt_done++;
				$this->info("immagini non riprocessate: $nt_done (hotel id: ". $hotel->id ." / file: $file)");
			}        
		}
		$this->info("immagini riprocessate: $done");
    }
    
    protected function nuoveImmagini_9_8_mobile($id=null)
    {
	    if ($id != null)
	     	$hotels = Hotel::select("id")->where("id" , $id)->get();
	    else
    	 	$hotels = Hotel::select("id")->get();
    	 	
		$this->info("hotels estratti dal DB: ".count($hotels));
		$done = 0;
		$nt_done = 0;
		
		foreach($hotels as $hotel){
			
			$imgs = ImmagineGallery::where("hotel_id", $hotel->id)
			->orderBy('position', 'asc')
			->get();
			
			//->take(1)
			//->first();
			
			if (count($imgs) > 0)
				$this->info("hotel : " . $hotel->id . "  immagini trovate: " . count($imgs));
			
			$c = 0;
			
			foreach($imgs as $img):
				
				$c++;
				
				if(!empty($img->foto)){
					
					$file = public_path(self::GALLERY_IMG_PATH . "800x538/".$img->foto);
					$dest = public_path(self::GALLERY_IMG_PATH . "360x320/");
					
					if(file_exists($file)){
						
						try{
							
							$imagev = new ImageVersionHandler;
							$uploaded_filename = File::name($file);
							$imagev->setImageBasename($uploaded_filename);
							$imagev->loadOriginalFromPath($file);
							$imagev->process("crop",  $dest , 360, 320); 
							$done++;
							$this->info("immagine creata: $c / (hotel id: ". $hotel->id ." / file: $file, $dest)");
							
						} catch (\Exception $e){
							
							$this->error($e->getMessage());
							$this->info("---------------------------------------------------------------------");
							$this->info("immagini non trovata $hotel->id: $done");
							$this->info("---------------------------------------------------------------------");
							
						}
					}
				} 
				 
			endforeach;  
		}
		
		$this->info("immagini riprocessate: $done/" . count($hotels));
		
    }
    
    protected function nuoveImmagini_220_220_desktop($id=null)
    {
	    if ($id != null)
	     	$hotels = Hotel::select("id")->where("id" , $id)->get();
	    else
    	 	$hotels = Hotel::select("id")->get();
    	 	
		$this->info("hotels estratti dal DB: ".count($hotels));
		$done = 0;
		$nt_done = 0;
		
		foreach($hotels as $hotel){
			
			$imgs = ImmagineGallery::where("hotel_id", $hotel->id)
			->orderBy('position', 'asc')
			->get();
					
			if (count($imgs) > 0)
				$this->info("hotel : " . $hotel->id . "  immagini trovate: " . count($imgs));
			
			$c = 0;
			
			foreach($imgs as $img):
				
				$c++;
				
				if(!empty($img->foto)){
					
					$file = public_path(self::GALLERY_IMG_PATH . "800x538/".$img->foto);
					$dest = public_path(self::GALLERY_IMG_PATH . "220x220/");
					
					if(file_exists($file)){
						
						try{
							
							$imagev = new ImageVersionHandler;
							$uploaded_filename = File::name($file);
							$imagev->setImageBasename($uploaded_filename);
							$imagev->loadOriginalFromPath($file);
							$imagev->process("crop",  $dest , 220, 220); 
							$done++;
							$this->info("immagine creata: $c / (hotel id: ". $hotel->id ." / file: $file, $dest)");
							
						} catch (\Exception $e){
							
							$this->error($e->getMessage());
							$this->info("---------------------------------------------------------------------");
							$this->info("immagini non trovata $hotel->id: $done");
							$this->info("---------------------------------------------------------------------");
							
						}
					}
				} 
				 
			endforeach;  
		}
		
		$this->info("immagini riprocessate: $done/" . count($hotels));
		
    }

	protected function aggiornamento_listing()
    {

      $hotels = Hotel::select("id", "listing_img")->attivo()->get();

      $this->info("hotels estratti dal DB: ".count($hotels));

      $done = 0;
      $nt_done = 0;

      foreach($hotels as $hotel)
        {
	        
        $img = ImmagineGallery::where("hotel_id", $hotel->id)
          ->orderBy('position', 'asc')
          ->take(1)
          ->first();

        if(!empty($img->foto))
            {
			
			$fallo = false;
			$file = public_path("images/listing/360x200/".$img->foto);
			
			if(!file_exists($file)) {
				
            	$file = public_path("images/gallery/800x538/".$img->foto);
            	
   				if(file_exists($file)) {
					$fallo = true;
				}

			}
				
            if($fallo)
                {
                try
                    {
					
                    $imagev = new ImageVersionHandler;

                    $uploaded_filename = File::name($file);

                    // mantengo il filename esattamente invariato
                    $imagev->setImageBasename($uploaded_filename);
                    //$backup_path = storage_path('original_images/listing');
                    $backup_path = config("app.storage_images_path"). "/original_images/listing";
                    $imagev->enableOriginalBackup($backup_path);
                    $imagev->loadOriginalFromPath($file);

                    foreach(ImmagineGallery::getImagesVersions() as $v)
                      {
                      $imagev->process($v["mode"], $v["basedir"], $v["width"], $v["height"]);
                      }

                    $hotel->update(["listing_img" => $img->foto]);
                    
                    
                    $this->info($hotel->id ." " .$file );
                    

                    $done++;

                    if(!($done % 50))
                        $this->info("immagini riprocessate: $done");

                    }
                  catch (\Exception $e)
                    {
                    $this->error($e->getMessage());
                    }
                }
                
            } else {
	            
	             $nt_done++;
	             $this->info("immagini non riprocessate: $nt_done (hotel id: ". $hotel->id ." / file: $file)");
	             
            }        
        
        }

      $this->info("immagini riprocessate: $done");

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
	
	/*
		crea le immagini partendo dallo storage e a cascata dalle immagnini che trova
	    $this->listing();
	    $this->vetrine();
	 */
	    $this->gallery();
	
	
	/*
		ricrea la vecchia immagine 220x148
		$this->vecchie_img();
	*/
	
	//$this->aggiornamento_listing();
	
	/* 
	 * Crea le immagini 9/8 per la pagina scheda mobile
	 */
	//$this->nuoveImmagini_9_8_mobile();	
	
	/**
	 * Nuove foto quadrate listing
	 */
	
	//$this->nuoveImmagini_220_220_desktop();
	
	
    }
}
