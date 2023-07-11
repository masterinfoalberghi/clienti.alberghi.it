<?php

namespace App\library;

use Exception;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class ImageVersionHandler
{

    /**
     * Percorso immagine uploadata (es: /tmp/phpCxiDHO)
     * @var string
     */

    protected $imageRealPath;

    /**
     * La parte del filename dell'immagine finale esclusa l'estensione,
     * rappresenta solo la parte "manuale" perchè per volontà staff IA
     * ci sarà sempre una parte "automatica" che rappresenta il filename originale
     * @var string
     */

    protected $image_basename;

    /**
     * Il path alla directory che conterrà il backup dell'immagine originalmente uploadata
     * @var string
     */

    protected $backup_path;

    /**
     * Estensione immagine uploadata
     * @var string
     */

    protected $uploaded_image_extension;

    /**
     * Imposta la parte del filename dell'immagine precedente all'estensione
     * @param string $basename
     * @return $this
     */

    public function setImageBasename($basename)
    {
        $this->image_basename = $basename;
    }


    /**
     * Abilita il backup dell'immagine originale
     * @param  string $path Il path alla directory che conterrà il backup dell'immagine originalmente uploadata
     */

    public function enableOriginalBackup($path)
    {
        $this->backup_path = $path;
    }


    /**
     * Ritorna il filename completo, quindi comprensivo dell'estensione
     * @return string
     */

    public function getImageFilename()
    {
        return "{$this->image_basename}.{$this->uploaded_image_extension}";
    }


    /**
     * Carica l'immagine originale da un path locale
     * @param  string $path
     */

    public function loadOriginalFromPath($path)
    {
        if (file_exists($path)) {
            $this->imageRealPath = $path;

            $this->uploaded_image_extension = File::extension($path);

            if ($this->backup_path)
                $this->backupOriginal();
        } else
            throw new Exception("Impossibile aprire il file origianle $path");
    }

    private function _getMessageError($error)
    {

        $error_msg = '';

        switch ($error) {
            case UPLOAD_ERR_OK:

                // all is OK
                break;

            case UPLOAD_ERR_INI_SIZE:
                $error_msg = $this->translate('uploaded_too_big_ini');
                break;

            case UPLOAD_ERR_FORM_SIZE:
                $error_msg = $this->translate('uploaded_too_big_html');
                break;

            case UPLOAD_ERR_PARTIAL:
                $error_msg = $this->translate('uploaded_partial');
                break;

            case UPLOAD_ERR_NO_FILE:
                $error_msg = $this->translate('uploaded_missing');
                break;

            case @UPLOAD_ERR_NO_TMP_DIR:
                $error_msg = $this->translate('uploaded_no_tmp_dir');
                break;

            case @UPLOAD_ERR_CANT_WRITE:
                $error_msg = $this->translate('uploaded_cant_write');
                break;

            case @UPLOAD_ERR_EXTENSION:
                $error_msg = $this->translate('uploaded_err_extension');
                break;

            default:
                $error_msg = $this->translate('uploaded_unknown') . ' (' . $error . ')';
        } // end switch

        return $error_msg;
    }

    /**
     * Carica l'immagine originale via file upload
     * @param  $file
     */

    public function loadOriginalFromUpload($file)
    {

        if ($file !== null) {

            /**
             * instance of the Symfony\Component\HttpFoundation\File\UploadedFile class
             * UploadedFile {#29 ▼
             *  -test: false
             * -originalName: "17_Hotel_Sabrina_Rimini_17h_big.jpg"
             * -mimeType: "image/jpeg"
             * -size: 122646
             * -error: 0
             * }
             */

            $error = $file->getError();

            $error_msg = $this->_getMessageError($error);

        }

        if ($error_msg)
            throw new Exception("Errore nell'upload dell'immagine: $error_msg");


        // estensione immagine uploadata
        $this->uploaded_image_extension = $file->getClientOriginalExtension();

        // percorso immagine uploadata (es: /tmp/phpCxiDHO)
        $this->imageRealPath = $file->getRealPath();

        if ($this->backup_path)
            $this->backupOriginal();

    }

    /**
     * Si cura del fatto che il path passato esista
     * @param  string $path
     */

    protected function ensurePathExists($path)
    {   

        return true;
        // if (!file_exists($path))
        //     mkdir($path, 0777, true);

    }


    /**
     * Ritorna costruendolo il path all'immagine
     * @param  string $basedir
     * @param  string $filename
     * @param  string $extension
     * @return string
     */
    protected function getImagePath($basedir, $filename, $extension)
    {
        $this->ensurePathExists($basedir);
        $path = rtrim($basedir, "/");
        $path .= "/$filename.$extension";
        return $path;
    }

    /**
     * Salva una copia di backup dell'immagine originale
     */

    public function backupOriginal()
    {   
        
        $image = Image::make($this->imageRealPath);
        $destination_path = $this->getImagePath($this->backup_path, $this->image_basename, $this->uploaded_image_extension);
        Storage::disk("s3")->put($destination_path, $image->stream());

    }

    public function process($mode, $basedir, $width, $height)
    {
        if (!in_array($mode, array("crop", "resize", "original")))
            throw new Exception("È stato richiesto un processing dell'immagine in modalità $mode, ma non corrisponde a nessun processing conosciuto ('crop', 'resize')");
        
        if ($this->imageRealPath === null)
            throw new Exception("Non posso processare l'immagine perchè l'immagine di partenza non risulta caricata.");

        $image = Image::make($this->imageRealPath)->encode('WebP');
        $orig_width = $image->width();
        $orig_height = $image->height();
        
        $yc = $width * $orig_height / $orig_width;

        if ($yc >= $height) {
            $image->resize($width, null, function ($constraint)
            {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        } else {
            $image->resize(null, $height, function ($constraint)
            {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
        }
        
        if ($orig_width > $width || $orig_height > $height)
            $image->crop($width, $height);

        $image->resizeCanvas($width, $height , "center", false, array(0,0,0,0));
        $destination_path = $this->getImagePath($basedir, $this->image_basename, $this->uploaded_image_extension);

        Storage::disk("s3")->put($destination_path, $image->stream());
        
        // return $this->$mode($basedir, $width, $height);

    }

    // public function crop($basedir, $width, $height)
    // {
        
    //     if ($this->imageRealPath === null)
    //         throw new Exception("Non posso processare l'immagine perchè l'immagine di partenza non risulta caricata.");
        
    //     $image = Image::make($this->imageRealPath);
    //     $orig_width = $image->width();
    //     $orig_height = $image->height();
    //     $destination_path = $this->getImagePath($basedir, $this->image_basename, $this->uploaded_image_extension);
        
    //     if ($orig_width > $width || $orig_height > $height) {

    //         $image->fit($width, $height);
    //         Storage::disk("s3")->put($destination_path, $image->stream());
    //         //$image->save($destination_path);

    //     } elseif ($orig_width == $width && $orig_height == $height) {

    //         /** Immagine delle dimensioni richieste, va bene lei */
    //         Storage::disk("s3")->put($destination_path, $image->stream());
    //         //$image->save($destination_path);

    //     } elseif ($orig_width < $width || $orig_height < $height) {

    //         /** Immagine più piccola, la devo cntrare */
    //         $this->saveCenteredImage($image, $width, $height, $destination_path);

    //     }

    //     // /** ALLA FINE OTIMIZZO L'IMMAGINE CON OptimJpg */
    //     // $command = '/usr/bin/jpegoptim --strip-all --max=85 ' . escapeshellarg($destination_path);
    //     // $out = shell_exec($command);
    
    // }

    // public function original($basedir, $width = 0, $height = 0)
    // {

    //     $image = Image::make($this->imageRealPath);
    //     $destination_path = $this->getImagePath($basedir, $this->image_basename, $this->uploaded_image_extension);
    //     Storage::disk("s3")->put($destination_path, $image->stream());

    //     //$image->save($destination_path);

    //     // $command = '/usr/bin/jpegoptim --strip-all --max=85 ' . escapeshellarg($destination_path);
    //     // $out = shell_exec($command);

    // }

    // public function resize($basedir, $width, $height)
    // {

    //     if ($this->imageRealPath === null)
    //         throw new Exception("Non posso processare l'immagine perchè l'immagine di partenza non risulta caricata.");

    //     $image = Image::make($this->imageRealPath);
    //     $orig_width = $image->width();
    //     $orig_height = $image->height();

    //     /** Immagine più grande, faccio il resize */
    //     if ($orig_width > $width || $orig_height > $height) {
    //         $image->resize($width, $height, function ($constraint) {
    //             $constraint->aspectRatio();
    //             $constraint->upsize();
    //         });
    //     }

    //     $destination_path = $this->getImagePath($basedir, $this->image_basename, $this->uploaded_image_extension);
    //     Storage::disk("s3")->put($destination_path, $image->stream());
    //     //$image->save($destination_path);

    //     /* ALLA FINE OTIMIZZO L'IMMAGINE CON OptimJpg */
    //     //$command = '/usr/bin/jpegoptim --strip-all --max=85 ' . escapeshellarg($destination_path);
    //     //$out = shell_exec($command);

    // }/* end resize */

    // protected function saveCenteredImage($image, $width, $height, $destination_path)
    // {
    //     // create new image with background color
    //     $background = Image::canvas($width, $height, 'rgba(255,255,255,0.2)');

    //     // insert resized image centered into background
    //     $background->insert($image, 'center');

    //     // save or do whatever you like
    //     Storage::disk("s3")->put($destination_path, $background->stream());
    //     //$background->save($destination_path);
    // }
}
