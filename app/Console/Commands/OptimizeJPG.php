<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class OptimizeJPG extends Command
    {
        /**
         * The name and signature of the console command.
         *
         * @var string
         */
        protected $signature = 'image:optimize_jpg';

        /**
         * The console command description.
         *
         * @var string
         */
        protected $description = 'Ottimizza le immagini mediante la libreria JPEGOptim';

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
        //$folders = ['listing','gallery'];
        $folders = ['home','spothome'];

        foreach ($folders as $folder) 
          {

          $all_images = Storage::disk($folder)->allFiles();
          
          foreach ($all_images as $image) 
            {
            
            $out = "";
            
            $command = '/usr/bin/jpegoptim --strip-all --max=85 '.escapeshellarg(public_path('images/'.$folder.'/'.$image));
            $out = shell_exec($command);

            $this->info($out);

            } /* foreach images */

          }/* foreach folder*/

        } /* end handle*/
    }
