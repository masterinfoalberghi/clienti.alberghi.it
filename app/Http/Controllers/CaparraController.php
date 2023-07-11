<?php

namespace App\Http\Controllers;

use App\Caparra;
use App\Hotel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CaparraController extends Controller
{

    private function _mask($string)
    {

        $len = strlen($string);
        $showLen = floor($len / 2);
        $str_arr = str_split($string);

        for ($ii = $showLen; $ii < $len; $ii++) {
            $str_arr[$ii] = '*';
        }

        return implode('', $str_arr);

    }

    private function _maskEmail($email)
    {
        // maschero l'email
        $explode_email = explode(".", $email);
        $ext = "." . end($explode_email);
        $removed = array_pop($explode_email);
        $new_email = implode(".", $explode_email);
        $empart = explode("@", $new_email);

        $empart[0] = Self::_mask($empart[0]);
        $empart[1] = Self::_mask($empart[1]);
        return implode('@', $empart) . $ext;

    }

    private function _getEmail($email)
    {

        // Controllo che non siano più email
        if (strpos($email, ",")) {
            $emails = explode(",", $email);
            foreach ($emails as $e) {
                if (strpos($e, "1clicksuite") === false) {
                    $email = $e;
                    break;
                }
            }
        }

        return $email;

    }

    private function _getPhone($phone)
    {

        if ($phone == "") {
            return false;
        }

        if (strpos($phone, " ") === true) {
            $phones = explode(" ", $phone);
            $phone = $phones[0];
        }

        $phone = str_replace(["-", " "], "", $phone);
        return $phone;

    }

    private function _verifyChoice($hotel)
    {

        $email = Self::_getEmail($hotel->email);
        $phone = Self::_getPhone($hotel->telefono);
        $mobile = Self::_getPhone($hotel->cell);
        $fax = Self::_getPhone($hotel->fax);
        $whatsapp = Self::_getPhone($hotel->whatsapp);

        $verify_method = [];
        $verify_method["email"] = Self::_maskEmail($email);
        $phone ? $verify_method["phone"] = Self::_mask($phone) : "";
        $mobile ? $verify_method["mobile"] = Self::_mask($mobile) : "";
        $fax ? $verify_method["fax"] = Self::_mask($fax) : "";
        $whatsapp ? $verify_method["whatsapp"] = Self::_mask($whatsapp) : "";

        $verify_data = [];
        $verify_data["email"] = $email;
        $phone ? $verify_data["phone"] = $phone : "";
        $mobile ? $verify_data["mobile"] = $mobile : "";
        $fax ? $verify_data["fax"] = $fax : "";
        $whatsapp ? $verify_data["whatsapp"] = $whatsapp : "";

        $method = array_rand($verify_method, 1);

        return [$method, $verify_method, $verify_data];

    }

    public function index()
    {

        $hotel = Hotel::with(["localita", "immaginiGallery"])->attivo()->get();

        return View::make(
            'caparra.caparra', ["hotel" => $hotel]
        );

    }

    public function periods($hotel_id)
    {

        $periods = Caparra::where("hotel_id", $hotel_id)->get();
        $hotel = Hotel::find($hotel_id);

        if ($hotel->attivo == 0) {
            return View::make(
                'caparra.error-disabled', ["hotel" => $hotel, "deposit" => $periods]
            );
        }

        if ($periods->count() > 0) {
            return View::make(
                'caparra.error', ["hotel" => $hotel, "deposit" => $periods]
            );
        }

        $hotel = Hotel::with(["localita", "immaginiGallery"])->where("id", $hotel_id)->first();

        return View::make(
            'caparra.periods', ["hotel" => $hotel, "periods" => $periods]
        );

    }

    public function store(Request $request)
    {

        $hotel_id = $request->get("hotel_id");
        $options = json_decode($request->get("options"));

        if (count($options) > 0) {

            foreach ($options as $option):

                $caparra_period = new Caparra();
                // $caparra_period->ip = $request->ip();
                $caparra_period->hotel_id = $hotel_id;
                $caparra_period->option = $option->option;
                $caparra_period->from = Carbon::createFromFormat('d/m/Y', $option->from);
                $caparra_period->to = Carbon::createFromFormat('d/m/Y', $option->to);
                $caparra_period->day_before = $option->day_before;
                $caparra_period->perc = $option->perc;
                $caparra_period->month_after = $option->month_after;
                $caparra_period->enabled = false;
                $caparra_period->save();

            endforeach;

            return response()->json(['success' => 'success'], 200);

        } else {

            response()->json(['success' => 'error'], 200);

        }

    }

    public function confirm($hotel_id)
    {

        $hotel = Hotel::find($hotel_id);
        $periods = Caparra::where("hotel_id", $hotel_id)->get();

        if ($hotel->attivo == 0) {
            return View::make(
                'caparra.error-disabled', ["hotel" => $hotel, "deposit" => $periods]
            );
        }

        if ($periods->count() == 0) {
            return redirect('/caparra/' . $hotel->id);
        } else {
            foreach ($periods as $period):
                if ($period->enabled == true) {
                    return View::make(
                        'caparra.error', ["hotel" => $hotel, "deposit" => $periods]
                    );
                }
                break;
             endforeach;
        }

        $v_method = Self::_verifyChoice($hotel);

        return View::make(
            'caparra.confirm', ["hotel" => $hotel, "periods" => $periods, "verify_method" => $v_method[1], "method" => $v_method[0]]
        );
    }

    public static function confirmStore (Request $request)
    {

        $data = json_decode($request->get("data"));
        $hotel = Hotel::find($data->hotel_id);

        $v_method = Self::_verifyChoice($hotel);

        if ($data->data == $v_method[2][$data->method]) {

            Caparra::where("hotel_id", $hotel->id)->update(["enabled" => true]);
            return response()->json(['success' => 'success'], 200);

        }

        return response()->json(['success' => 'error'], 200);

    }

    public function helpRequestThanks(Request $request)
    {
        return View::make('caparra.thanks');
    }

    public function helpRequest(Request $request, $hotel_id)
    {

        $hotel = Hotel::find($hotel_id);

        if (is_null($hotel)) {
            die("L'hotel che richiede assistenza non esiste");
        }

        $from = "no-reply@info-alberghi.com";
        $nome = "Richiesta assistenza caparre";
        $to = 'assistenza@info-alberghi.com';
        $bcc = ["luciobonini@gmail.com"];

        $oggetto = "hotel $hotel->nome (ID = $hotel->id) richiede assietnza compilazione caparre";

        try {

            Mail::send('emails.help_request_caparre',
                compact(
                    'hotel'
                ), function ($message) use ($from, &$bcc, $oggetto, $nome, $to) {

                    $message->from($from, $nome);
                    $message->to($to);
                    $message->bcc($bcc);
                    $message->subject($oggetto);

                }
            );

        } catch (\Exception $e) {
            echo "Error " . $e->getMessage();
        }

        return redirect()->route('help_request_thanks');

    }
}
