<?php

namespace App\Console\Commands;

use DB;
use App\Utility;
use Illuminate\Console\Command;

class cambioTestiHotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:newTextHotel';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Formatta i testi delle schede hotel correttamente';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
	
}
