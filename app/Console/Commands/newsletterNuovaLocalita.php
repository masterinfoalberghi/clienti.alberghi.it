<?php

namespace App\Console\Commands;

use App\Hotel;
use App\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class newsletterNuovaLocalita extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:localita';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spedisce una newsletter di presentazione hai nuovi hotel';

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
     * @return int
     */
    public function handle()
    {
        
        /** Bologna */

        $from    = "andreabernabei@info-alberghi.com";
        $nome    = "Info Alberghi srl - Maria Andrea Bernabei";
        $oggetto = "Ora fai parte del nostro nuovo progetto!";
        
        /** Bologna */
        // //$hotels = [32150,32152,32165,32169,32170,32171,32172,32174,32180,32184,32188,32192,32195,32204,32209,32214,32225,32228,32235,32238,32241,32242,32246,32248,32249,32250,32251,32252,32254,32255,32256,32257,32258,32259,32260,32261,32262,32263,32266,32267,32269,32270,32271,32273,32274,32275,32276,32277,32278,32280,32281,32282,32283,32284,32287,32288,32289,32290,32292,32293,32294,32298,32299,32300,32302,32304,32305,32306,32307,32310,32312,32313,32314,32316,32319,32320,32323,32325,32326,32327,32328,32329,32330,32331,32332,32335,32338,32339,32340,32341,32342,32343,32345,32346,32348,32353,32356,32357,32360,32362,32365,32367,32368,32369,32370,32371,32372,32373,32375,32376,32378,32379,32381,32385,32386,32388,32390,32391,32393,32394,32396,32398,32399,32400,32402,32403,32405,32406,32409,32412,32413,32414,32415,32416,32419,32420,32421,32422,32426,32429,32432,32437,32440,32441,32442,32448,32456,32458];
        
        /** Firenze */
        // $hotels = [28667,28669,28671,28678,28679,28681,28682,28683,28684,28686,28687,28690,28692,28693,28695,28698,28700,28701,28702,28705,28708,28709,28711,28714,28715,28718,28719,28722,28725,28728,28730,28732,28740,28741,28743,28745,28746,28747,28748,28749,28750,28754,28755,28757,28759,28763,28764,28766,28767,28769];
        // $hotels = [28771,28773,28780,28782,28783,28785,28786,28787,28791,28797,28802,28805,28806,28808,28810,28815,28821,28824,28828,28835,28837,28839,28840,28842,28844,28845,28847,28848,28851,28859,28861,28863,28865,28868,28872,28878,28881,28883,28885,28887,28889,28890,28896,28900,28906,28912,28914,28915,28918,28919];
        // $hotels = [28921,28925,28926,28936,28938,28939,28946,28950,28970,28975,28979,28980,28984,28989,28990,28991,28993,28999,29002,29009,29011,29018,29026,29028,29029,29033,29035,29036,29037,29044,29046,29047,29048,29054,29055,29057,29058,29060,29064,29067,29070,29073,29077,29078,29080,29093,29094,29100,29101,29105,29107,29110,29114];

        /** Siena */
        $hotels = [5000021,5000026,5000036,5000037,5000038,5000039,5000043,5000044,5000045,5000046,5000047,5000048,5000049,5000050,5000051,5000052,5000053,5000054,5000055,5000056,5000058,5000059,5000060,5000061,5000062,5000063,5000064,5000065,5000066,5000067,5000068,5000069,5000070,5000071,5000072,5000073,5000074,5000075,5000076,5000077,5000078,5000079,5000080,5000081,5000082,5000083];
        //$hotels = [5000021];
        $email_template =  "newsletter_localita_siena";

        $hotels_to_contact = Hotel::whereIn("id", $hotels)->get();
                                           
        foreach($hotels_to_contact as $hotel_to_contact):

            $url = "https://www.info-alberghi.com/" . $hotel_to_contact->slug_it;
            $to = $hotel_to_contact->email;

            try {

                Utility::swapToSendGrid();
                Mail::send("emails." . $email_template,
                compact(
                    "oggetto",
                    "url"
                ), function ($message) use ($from, $nome, $oggetto, $to ) {
                    $message->from($from, $nome);
                    $message->replyTo($from);
                    $message->to($to);
                    $message->bcc("giovanni@info-alberghi.com");
                    $message->subject($oggetto);
                });

                $hotel_to_contact->newsletter_send = 1;
                $hotel_to_contact->save();

                echo "hotel id: " . $hotel_to_contact->id . " contattato\n";

            } catch (\Exception $e) {

                echo "----> hotel id: " . $hotel_to_contact->id . " non contattato - ";
                echo "error " . $e->getMessage() . "\n";

            }

        endforeach;
        

    }
}
