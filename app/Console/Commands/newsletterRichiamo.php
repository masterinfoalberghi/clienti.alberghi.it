<?php

namespace App\Console\Commands;

use App\Hotel;
use App\Utility;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class newsletterRichiamo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:richiamo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Spedisce una newsletter di richiamo hai nuovi hotel';

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
        $oggetto = "CIR: inviacelo subito!";
        
        $hotels = [
            32150,32152,32165,32169,32170,
            32171,32172,32174,32180,32184,
            32188,32192,32195,32204,32209,
            32214,32225,32228,32235,32238,
            32241,32242,32246,32249,32250,
            32254,32255,32256,32257,32258,
            32259,32260,32261,32262,32263,
            32266,32267,32269,32270,32271,
            32273,32274,32275,32276,32278,
            32280,32281,32282,32283,32284,
            32287,32288,32289,32290,32292,
            32293,32294,32298,32299,32300,
            32304,32305,32306,32307,32310,
            32312,32314,32316,32319,32320,
            32323,32325,32326,32327,32330,
            32331,32332,32335,32338,32339,
            32342,32343,32345,32346,32348,
            32353,32356,32357,32360,32365,
            32367,32368,32369,32370,32371,
            32372,32376,32378,32379,32381,
            32385,32386,32388,32390,32391,
            32393,32394,32398,32399,32403,
            32405,32406,32409,32412,32413,
            32414,32415,32416,32419,32420,
            32421,32426,32429,32432,32437,
            32440,32441,32442,32448,32456,32458];

        $hotels_to_contact = Hotel::whereIn("id", $hotels)->get();
                                           
        foreach($hotels_to_contact as $hotel_to_contact):

            $url = "https://www.info-alberghi.com/" . $hotel_to_contact->slug_it;
            $to = $hotel_to_contact->email;

            try {

                Utility::swapToSendGrid();
                Mail::send("emails.newsletter_richiamo",
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

                $hotel_to_contact->save();
                echo "hotel id: " . $hotel_to_contact->id . " contattato\n";

            } catch (\Exception $e) {

                echo "----> hotel id: " . $hotel_to_contact->id . " non contattato - ";
                echo "error " . $e->getMessage() . "\n";

            }

        endforeach;
        

    }
}
