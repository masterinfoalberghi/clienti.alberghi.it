<?php

namespace App\Console\Commands;

use App\DescrizioneHotel;
use App\DescrizioneHotelLingua;
use Illuminate\Console\Command;

class duplicate_sheet_hotel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'revision:duplicate';

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
        $alltext = DescrizioneHotel::all();

        foreach($alltext as $text): 

            $newText = new DescrizioneHotel();
            $newText->hotel_id = $text->hotel_id;
            $newText->video_url = $text->video_url;
            $newText->revision_name = "Covid-19";
            $newText->online = 0;
            $newText->save();

            $alltextLang = DescrizioneHotelLingua::where("master_id", $text->id)->get();
            
            foreach($alltextLang as $textLang):

                $newTextLang = new DescrizioneHotelLingua();
                $newTextLang->master_id = $newText->id;
                $newTextLang->lang_id = $textLang->lang_id;
                $newTextLang->testo = $textLang->testo;
                $newTextLang->save();

            endforeach;

        endforeach;
    }
}
