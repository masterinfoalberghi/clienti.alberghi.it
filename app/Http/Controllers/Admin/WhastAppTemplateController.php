<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\WhatsappTemplate;
use Illuminate\Support\Facades\Validator;
use App\Hotel;

class WhastAppTemplateController extends AdminBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $hotel_id = $this->getHotelId();

        $wa_templates = WhatsappTemplate::where('hotel_id', $hotel_id)->orderBy('created_at','desc')->get();

        $wa_template = new WhatsappTemplate;
        $param = "form";

        return view('admin.whatsapp_non_rubrica', compact('wa_template', 'param', 'wa_templates'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    Validator::make($request->all(), [
                    'titolo' => 'required',
                    'testo' => 'required',
            ])->validate();
    
    $hotel = Hotel::find($this->getHotelId());

    $wa_template = $hotel->wa_templates()->create($request->all());

    return redirect('admin/whatsapp-non-rubirca/lista')->with('status','Template creato correttamente');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $wa_template = WhatsappTemplate::find($id);

        $param = "form";

        $hotel_id = $this->getHotelId();

        $wa_templates = WhatsappTemplate::where('hotel_id', $hotel_id)->orderBy('created_at','desc')->get();

        return view('admin.whatsapp_non_rubrica', compact('wa_template', 'param', 'wa_templates'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Validator::make($request->all(), [
                        'titolo' => 'required',
                        'testo' => 'required',
                ])->validate();
        
        $wa_template = WhatsappTemplate::find($id);

        $wa_template->update($request->all());

        //dd($wa_template);

        return redirect('admin/whatsapp-non-rubirca/lista')->with('status','Template modificato correttamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        WhatsappTemplate::destroy($id);

        return redirect('admin/whatsapp-non-rubirca/lista')->with('status','Template eliminato correttamente');
    }
}
