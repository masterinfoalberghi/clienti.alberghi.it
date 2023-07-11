<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\WhatsappTemplate;

class WhastAppController extends AdminBaseController
{
    public function index()
    	{
			$hotel_id = $this->getHotelId();
    	$wa_templates = WhatsappTemplate::where('hotel_id', $hotel_id)->orderBy('created_at','desc')->get();
    	$param = "index";
    	return view('admin.whatsapp_non_rubrica', compact('wa_templates', 'param'));
    	}

    public function lista()
    	{
			$hotel_id = $this->getHotelId();
    	$wa_templates = WhatsappTemplate::where('hotel_id', $hotel_id)->orderBy('created_at','desc')->get();
    	$param = "lista";
    	return view('admin.whatsapp_non_rubrica', compact('wa_templates', 'param'));
    	}


}
