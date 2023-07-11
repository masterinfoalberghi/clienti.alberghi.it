<?php

namespace App\Http\Controllers;

use App\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class BonusController extends Controller
{
    private function _mask($string)
    {

        $len = strlen($string);
        $showLen = $len-3; //floor($len / 2);
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
        $method = "phone";

        return [$method, $verify_method, $verify_data];

    }

    public function index()
    {

        $hotel = Hotel::with(["localita", "immaginiGallery"])->attivo()->get();

        return View::make(
            'bonusvacanza.bonusvacanza', ["hotel" => $hotel]
        );

    }

    public function bonus($hotel_id)
    {

        $hotel = Hotel::find($hotel_id);

        if ($hotel->attivo == 0) {
            return View::make(
                'bonusvacanza.error-disabled', ["hotel" => $hotel]
            );
        }

        return View::make(
            'bonusvacanza.accettazione', ["hotel" => $hotel]
        );

    }

    public function store(Request $request)
    {

        $hotel_id = $request->get("hotel_id");
        $hotel = Hotel::find($hotel_id);
        $hotel->bonus_vacanze_2020 = $request->get("option");
        $hotel->save();
        return response()->json(['success' => 'success'], 200);

    }

    public function confirm($hotel_id)
    {

        $hotel = Hotel::find($hotel_id);
        $v_method = Self::_verifyChoice($hotel);

        return View::make(
            'bonusvacanza.confirm', ["hotel" => $hotel, "verify_method" => $v_method[1], "method" => $v_method[0]]
        );

    }

    public function confirmStore(Request $request)
    {

        $data = json_decode($request->get("data"));
        $hotel = Hotel::find($data->hotel_id);

        $v_method = Self::_verifyChoice($hotel);

        if ($data->data == $v_method[2][$data->method]) {

            $hotel = Hotel::where("id", $hotel->id)->first();
            
            if ($hotel->bonus_vacanze_2020 == "-2")
                Hotel::where("id", $hotel->id)->update(["bonus_vacanze_2020" => "-1"]);
            elseif ($hotel->bonus_vacanze_2020 == "2") {
                Hotel::where("id", $hotel->id)->update(["bonus_vacanze_2020" => "1"]);
            }
                
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
