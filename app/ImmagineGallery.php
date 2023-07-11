<?php

/**
 * ImmagineGallery
 *
 * @author Info Alberghi Srl
 * 
 */
 
namespace App;

use File;
use Config;
use App\Hotel;
use App\ImmagineGalleryLingua;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ImmagineGallery extends Model
{

	/**
	 * Path immagini
	 */
	
	const GALLERY_PATH  = "images/gallery";
	const SPOT_PATH     = "images/romagna/spothome";
	const EVIDENZA_PATH = "images/romagna/pagine";

	/** Default per img gallery grandi */
	const SB_W = 1600;
	const SB_H = 1076;

	/** Defualt per le thumbsnail */
	const B_W = 65;
	const B_H = 55;

	/** tabella in cui vengono salvati i record */
	protected $table = 'tblImmaginiGallery';

	// attributi NON mass-assignable
	protected $guarded = ['id'];
  
	/* ------------------------------------------------------------------------------------
	 * EAGER LOADING
	 * ------------------------------------------------------------------------------------ */

	public function immaginiGallery_lingua()
	{
		return $this->hasMany('App\ImmagineGalleryLingua', 'master_id', 'id');
	}

	public function cliente()
	{
		return $this->belongsTo('App\Hotel', 'hotel_id', 'id');
	}



	/* ------------------------------------------------------------------------------------
	 * SCOPE
	 * ------------------------------------------------------------------------------------ */



	public function scopeListingApp($query)
	{
		return $query->where('listing_app', 1);
	}



	/* ------------------------------------------------------------------------------------
	 * METODI PUBBLICI
	 * ------------------------------------------------------------------------------------ */



	/**
	 * Versioni delle immagini per gli l'evidenza delle pagine
	 * 
	 * @access public
	 * @static
	 * @return Array
	 */
	
	public static function getImagesVersionsEvidenza()
	{

		return [
			"original" => ["mode" => "original",  "height" => "0",    "width" => "0",    "basedir" => "/" . self::EVIDENZA_PATH . "/original"],
			"500x500"  => ["mode" => "crop",      "height" => "500",  "width" => "500",  "basedir" => "/" . self::EVIDENZA_PATH . "/500x500"],
			"1400x300" => ["mode" => "crop",      "height" => "300",  "width" => "1400", "basedir" => "/" . self::EVIDENZA_PATH . "/1400x300"],
			"1920x400" => ["mode" => "crop",      "height" => "400",  "width" => "1920", "basedir" => "/" . self::EVIDENZA_PATH . "/1920x400"],
			"1400x700" => ["mode" => "crop",      "height" => "700",  "width" => "1400", "basedir" => "/" . self::EVIDENZA_PATH . "/1400x700"],
			"600x290"  => ["mode" => "crop",      "height" => "290",  "width" => "600",  "basedir" => "/" . self::EVIDENZA_PATH . "/600x290"],
			"775x225"  => ["mode" => "crop",      "height" => "225",  "width" => "775",  "basedir" => "/" . self::EVIDENZA_PATH . "/775x225"],
			"300x200"  => ["mode" => "crop",      "height" => "200",  "width" => "300",  "basedir" => "/" . self::EVIDENZA_PATH . "/300x200"],
		];
	}


	/**
	 * Versioni delle immagini per gli spot in homepage
	 * 
	 * @access public
	 * @static
	 * @return Array
	 */

	public static function getImagesVersionsSpot()
	{

		return [
			"600x290" => ["mode" => "crop", "height" => "290", "width" => "600", "basedir" => "/" . self::SPOT_PATH . "/600x290"],
            "1400x300" => ["mode" => "crop","height" => "300",  "width" => "1400", "basedir" => "/" . self::SPOT_PATH . "/1400x300"],
			"300x225" => ["mode" => "crop", "height" => "225", "width" => "300", "basedir" => "/" . self::SPOT_PATH . "/300x225"],
			"775x225" => ["mode" => "crop", "height" => "225", "width" => "775", "basedir" => "/" . self::SPOT_PATH . "/775x225"],
			"310x150" => ["mode" => "crop", "height" => "150", "width" => "310", "basedir" => "/" . self::SPOT_PATH . "/310x150"],
			"100x100" => ["mode" => "crop","height" => "100",  "width" => "100", "basedir" => "/" . self::SPOT_PATH . "/100x100"],
		];
	}


	/**
	 * Versioni delle immagini per la gallery
	 * 
	 * @access public
	 * @static
	 * @return Array
	 */
	 
	public static function getImagesVersions()
	{

		return [

            // Romagna
            "65x57"  =>    ["mode" => "crop", "width" => "65",  "height" => "57",      "basedir" => '/' . self::GALLERY_PATH . "/65x57/"],
			"113x99" =>    ["mode" => "crop", "width" => "113", "height" => "99",      "basedir" => '/' . self::GALLERY_PATH . "/113x99/"],
			"220x148" =>   ["mode" => "crop", "width" => "220", "height" => "148",     "basedir" => '/' . self::GALLERY_PATH . "/220x148/"],
			"220x200" =>   ["mode" => "crop", "width" => "220", "height" => "200",     "basedir" => '/' . self::GALLERY_PATH . "/220x200/"],
			"220x220" =>   ["mode" => "crop", "width" => "220", "height" => "220",     "basedir" => '/' . self::GALLERY_PATH . "/220x220/"],
            "360x200" =>   ["mode" => "crop", "width" => "360", "height" => "200",     "basedir" => '/' . self::GALLERY_PATH . "/360x200/"],
            "360x320" =>   ["mode" => "crop", "width" => "360", "height" => "320",     "basedir" => '/' . self::GALLERY_PATH . "/360x320/"],
			"720x400" =>   ["mode" => "crop", "width" => "720", "height" => "400",     "basedir" => '/' . self::GALLERY_PATH . "/720x400/"],
			"800x538" =>   ["mode" => "crop", "width" => "800", "height" => "538",     "basedir" => '/' . self::GALLERY_PATH . "/800x538/"],
			"1920x1080" => ["mode" => "crop", "width" => "1920", "height" => "1080",   "basedir" => '/' . self::GALLERY_PATH . "/1920x1080/"],

            // Italia
            // "360x270" =>   ["mode" => "crop", "width" => "360", "height" => "270",     "basedir" => '/' . self::GALLERY_PATH . "/it/360x270/"],
			// "800x600" =>   ["mode" => "crop", "width" => "800", "height" => "600",     "basedir" => '/' . self::GALLERY_PATH . "/it/800x600/"],
			// "1440x1080" => ["mode" => "crop", "width" => "1440", "height" => "1080",   "basedir" => '/' . self::GALLERY_PATH . "/it/1440x1080/"],
			// "360x202" =>   ["mode" => "crop", "width" => "360", "height" => "202",     "basedir" => '/' . self::GALLERY_PATH . "/it/360x202/"],
			// "720x400" =>   ["mode" => "crop", "width" => "720", "height" => "405",     "basedir" => '/' . self::GALLERY_PATH . "/it/720x405/"],
			// "1920x1080" => ["mode" => "crop", "width" => "1920", "height" => "1080",   "basedir" => '/' . self::GALLERY_PATH . "/it/1920x1080/"],

		];
	}

    public function getImg($version, $image_not_found_placeholder = false)
	{

		if (!$image_not_found_placeholder) 
			$image_not_found_placeholder = Config::get("image.image404");
		
		/*
	     * 1. Inizio
		 * 2. E' in una cartella esistente
		 * 3. E' un file esistente
		 * 4. copio il file nella cartella
		 * 5. eseguo il crop/resize
		 * 6. restituisco il file
		 */

		$path = Utility::assetsLoaded(self::GALLERY_PATH."/$version/{$this->hotel_id}/{$this->foto}", true, true);

		/**
		 * è stata definita ma è invalida (non c'è fisicamente sul filesystem o è illeggibile)
		 */
			
        if ($path == "113x99") {

            $version = "64x57";
            $path = Utility::assetsLoaded(self::GALLERY_PATH."$version/{$this->hotel_id}/{$this->foto}", true, true);

        } 

		return $path;
		
	}
	
	
	/**
	 * Cancella i file nelle cartelle selezionate.
	 * 
	 * @access public
	 * @return void
	 */
	 
	public function deleteFiles()
	{

		$ok = 0;
		$dirs = array_keys(ImmagineGallery::getImagesVersions());

		foreach ($dirs as $dir) {
			if ($this->getImg($dir) !== false) {
                $delete = Storage::disk("s3")->delete("/images/gallery/" . $dir ."/" . $this->hotel_id . "/" . $this->foto);
                if ($delete) $ok++;
			}
		}

		return;
	}


}
