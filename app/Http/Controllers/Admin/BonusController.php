<?php

namespace App\Http\Controllers\Admin;

use App\Hotel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BonusController extends AdminBaseController
{

    public function moderazione () 
    {
        //$cdms = Caparra::with(["hotel.localita"])->where("enabled", 0)->orderBy("hotel_id")->get();
        $hotels =  Hotel::where("bonus_vacanze_2020", 2)->orWhere("bonus_vacanze_2020",-2)->orderBy("id")->get();

        $hotel_si = Hotel::where("bonus_vacanze_2020", 1)->count();
        $hotel_si_attesa = Hotel::where("bonus_vacanze_2020", 2)->count();
        $hotel_no = Hotel::where("bonus_vacanze_2020", -1)->count();
        $hotel_no_attesa = Hotel::where("bonus_vacanze_2020", -2)->count();

        return view('admin.moderazione-bonus' , [
            "hotels" =>  $hotels,
            "hotel_si" => $hotel_si,
            "hotel_si_attesa" => $hotel_si_attesa,
            "hotel_no" => $hotel_no,
            "hotel_no_attesa" => $hotel_no_attesa,
        ]);
    }

    function storeModeratore (Request $request) 
    {
        
        $id = $request->get("id");
        $action = $request->get("action");
        $value = $request->get("value");
        $status_hotel = Hotel::where("id", $id)->first();
        
        if ($action == "status") {

            if ($value == "enabled" || $value == "disabled") {

                if ($status_hotel->bonus_vacanze_2020 == "2" && $value == "enabled")
                    Hotel::where("id", $id)->update(["bonus_vacanze_2020" => "1"]);
                else if ($status_hotel->bonus_vacanze_2020 == "1" && $value == "disabled")
                    Hotel::where("id", $id)->update(["bonus_vacanze_2020" => "2"]);
                else if ($status_hotel->bonus_vacanze_2020 == "-2" && $value == "enabled")
                    Hotel::where("id", $id)->update(["bonus_vacanze_2020" => "-1"]);
                else if ($status_hotel->bonus_vacanze_2020 == "-1" && $value == "disabled")
                    Hotel::where("id", $id)->update(["bonus_vacanze_2020" => "-2"]);

            } else
                Hotel::where("id", $id)->update(["bonus_vacanze_2020" => 0]);
            

        } else if ( $action == "delete" )
            Hotel::where("id", $id)->update(["bonus_vacanze_2020" => 0]);

        return response()->json(['success' => 'success'], 200);
        
    }

    function index () {

       
        $hotel_id = $this->getHotelId();
        $hotel = Hotel::where("id", $hotel_id)->first();
        return view('admin.bonus_index', ["hotel" => $hotel]);

    }

    function store (Request $request) 
    {   

        $data = json_decode($request->get("value"));
        $hotel_id = $this->getHotelId();
        Hotel::where("id", $hotel_id)->update(["bonus_vacanze_2020" => $data]);
        return response()->json(['success' => 'success'], 200);

    }


}
