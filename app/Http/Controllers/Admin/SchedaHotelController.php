<?php

namespace App\Http\Controllers\Admin;

use Langs;
use SessionResponseMessages;
use App\DescrizioneHotel;
use App\DescrizioneHotelLingua;
use App\Hotel;
use App\Utility;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;


class SchedaHotelController extends AdminBaseController
{

	private function _schedaHotelLingua($id, $revision = null)
	{	
		if ($revision)
			return DescrizioneHotel::
				with(['descrizioneHotel_lingua'])
					->where('id', $revision)
					->first();
		else
			return DescrizioneHotel::
				with(['descrizioneHotel_lingua'])
					->where('hotel_id', $id)
					->where("online", 1)
					->first();
	}


	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{

		$revision_id = $request->get("revision");
		$hotel_id = $this->getHotelId();
		$descrizioneHotel = $this->_schedaHotelLingua($hotel_id, $revision_id);
		$hotel = Hotel::find($hotel_id);


		if (is_null($descrizioneHotel)) {
			$hotel->createEmptyDescription();
			$descrizioneHotel = $this->_schedaHotelLingua($hotel_id);
		}

		$revisions = DB::table("tblDescrizioneHotel")->where("hotel_id", $hotel_id)->get();
		
		$data = [];
		$data['revisions'] = [];
		$data['hotel_id'] = $hotel_id;

		foreach ($descrizioneHotel->descrizioneHotel_lingua as $descrizioneHotel_lingua) {
			
			if (!Utility::is_JSON($descrizioneHotel_lingua->testo)):
				
				$data[$descrizioneHotel_lingua->lang_id] = array();
				$data[$descrizioneHotel_lingua->lang_id][0] = new \StdClass;
				$data[$descrizioneHotel_lingua->lang_id][0]->title = "";
				$data[$descrizioneHotel_lingua->lang_id][0]->subtitle = "";
				$data[$descrizioneHotel_lingua->lang_id][0]->mirror = "";
				$data[$descrizioneHotel_lingua->lang_id][0]->piscina = "";
				$data[$descrizioneHotel_lingua->lang_id][0]->spa = "";
				$data[$descrizioneHotel_lingua->lang_id][0]->testo = $descrizioneHotel_lingua->testo;				
			
			else:
				
				$data[$descrizioneHotel_lingua->lang_id] = json_decode($descrizioneHotel_lingua->testo);
						
			endif;
			
		}
		
		foreach($revisions as $revision):

			$data['revisions'][] = ["id" => $revision->id, "name" => $revision->revision_name, "date" => $revision->created_at, "update" => $revision->created_at, "online" => $revision->online];

			if (!is_null($revision_id)) {
				if ($revision_id == $revision->id) {
					$data['revisions_id_selected'] = $revision->id;
					$data['revisions_name_selected'] = $revision->revision_name;
				}
			} else {
				if ($revision->online == 1) {
					$data['revisions_id_selected'] = $revision->id;
					$data['revisions_name_selected'] = $revision->revision_name;
				}
			}

		endforeach;
		
		$data['video_url'] = $descrizioneHotel->video_url;
		return view('admin.scheda-hotel_edit', compact("data","hotel"));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request)
	{
		//
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
		
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		
	}



	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateLingua(Request $request)
	{
		
		/* cancello tutta "la parte in lingua" perché posso aver modificato più lingue nello stesso tempo !!!*/
		
		$hotel_id = $this->getHotelId();
		$revision_id = $request->get("resivion_id");
		Utility::clearCacheHotel($hotel_id);
		
		$descrizioneHotel = $this->_schedaHotelLingua($hotel_id, $revision_id);
		$descrizioneHotel->video_url = $request->get('video_url');
		$descrizioneHotel->revision_name = $request->get('revision_name');
		$descrizioneHotel->save();
		
		$title 		= $request->get("title");
		$subtitle 	= $request->get("subtitle");
		$mirror 	= $request->get("mirror");
		$testi 		= $request->get("testo");
		$piscina 	= $request->get("piscina");
		$spa 		= $request->get("spa");


		$arraytesto = array();
		
		foreach (Langs::getAll() as $lang):
		
			$testoIncollato = $request->get("pastetext_" . $lang);
			
			if ($testoIncollato != ""):
				
				$testoCoretto = trim($testoIncollato);
				$testoCoretto = str_replace("\t", "", $testoCoretto);
				$testoCoretto = str_replace("<p>", "", $testoCoretto);
				$testoCoretto = str_replace("</p>", "", $testoCoretto);
				$testoCoretto = str_replace("\r", "<br />", $testoCoretto);
				$testoCoretto = str_replace("\n", "", $testoCoretto);
	
				$testoCoretto = str_replace("</h4>", "</h4><p>" , $testoCoretto);
				$testoCoretto = str_replace("<h4>", "</p>!---#####---!<h4>" , $testoCoretto);
				$testoCoretto = str_replace("</h3>", "</h3><p>" , $testoCoretto);
				$testoCoretto = str_replace("  ", " " , $testoCoretto);
				$testoCoretto = preg_replace('@\>\s{1,}\<@','><',$testoCoretto);
				
				$testoCoretto = str_replace("<p><br /><br />", "<p>" , $testoCoretto);
				$testoCoretto = str_replace("<p><br />", "<p>" , $testoCoretto);
				$testoCoretto = str_replace("<br /><br /></p>", "</p>" , $testoCoretto);
				$testoCoretto = str_replace("<br /></p>", "</p>" , $testoCoretto);
				$testoCoretto = str_replace(". <", ".<" , $testoCoretto);		
				$testoCoretto = $testoCoretto . "</p>";
				
				$testoCorettoArray = explode("!---#####---!", $testoCoretto);
				$c = 0;
				
				foreach ($testoCorettoArray as $t):
					
					preg_match('/<h3>(.*?)<\/h3>/s', $t, $h3_matches);
					preg_match('/<h4>(.*?)<\/h4>/s', $t, $h4_matches);
					preg_match('/<p>(.*?)<\/p>/s', $t, $p_matches);
					
					$h3 = isset($h3_matches[1]) 	?  $h3_matches[1]: "";
					$h4 = isset($h4_matches[1]) 	?  $h4_matches[1]: "";
					$testo = isset($p_matches[1])  	?  $p_matches[1]: "";
					
					$arraytesto[$lang][$c] = array();
					$arraytesto[$lang][$c]["title"] 	= $h3;
					$arraytesto[$lang][$c]["subtitle"] 	= $h4;
					$arraytesto[$lang][$c]["mirror"] 	= "";
					$arraytesto[$lang][$c]["testo"] 	= $testo;
					$arraytesto[$lang][$c]["piscina"] 	= "";
					$arraytesto[$lang][$c]["spa"] 		= "";
					$c++;
					
				endforeach;
						
			else:
								
				$c=0;
				foreach ($testi[$lang] as $t):
							
					$arraytesto[$lang][$c] = array();
					$arraytesto[$lang][$c]["title"] 	= isset($title[$lang][$c]) 		? $title[$lang][$c]		: "";
					$arraytesto[$lang][$c]["subtitle"] 	= isset($subtitle[$lang][$c])	? $subtitle[$lang][$c]	: "";
					$arraytesto[$lang][$c]["mirror"] 	= isset($mirror[$lang][$c])		? $mirror[$lang][$c]	: "";
					$arraytesto[$lang][$c]["testo"] 	= isset($testi[$lang][$c]) 		? $testi[$lang][$c]		: "";
					$arraytesto[$lang][$c]["piscina"] 	= isset($piscina[$lang][$c]) 	? $piscina[$lang][$c]	: "";
					$arraytesto[$lang][$c]["spa"] 		= isset($spa[$lang][$c]) 		? $spa[$lang][$c]		: "";
					$c++;
					
				endforeach;
			
			endif;
			
		endforeach;

		foreach ($descrizioneHotel->descrizioneHotel_lingua as $descrizioneHotel_lingua) {
					
			$descrizioneHotel_lingua->testo = json_encode($arraytesto[$descrizioneHotel_lingua->lang_id]);
			$descrizioneHotel_lingua->save();
						
		}

		SessionResponseMessages::add("success", "Modifiche salvate con successo.");
		return SessionResponseMessages::redirect("admin/scheda-hotel?revision=" . $revision_id, $request);
		
	}

	public function online(Request $request)
	{

		DescrizioneHotel::where("hotel_id", $request->get("hotel_id"))->update(["online" => 0]);	
		DescrizioneHotel::where("id", $request->get("revision_id"))->update(["online" => 1]);	
		return response()->json(['success' => 'success', 'revision_id' => $request->get("revision_id")], 200);

	}

	public function duplicate(Request $request) 
	{
		
		$DescrizioneHotel = DescrizioneHotel::where("id", $request->get("revision_id"))->first();
		
		$newDescrizioneHotel = new DescrizioneHotel();
		$newDescrizioneHotel->hotel_id = $DescrizioneHotel->hotel_id;
		$newDescrizioneHotel->video_url = $DescrizioneHotel->video_url;
		$newDescrizioneHotel->revision_name = $request->get("revision_name");
		$newDescrizioneHotel->online = 0;
		$newDescrizioneHotel->save();

		$DescrizioneHotelLangs = DescrizioneHotelLingua::where("master_id", $request->get("revision_id"))->get();

		foreach($DescrizioneHotelLangs as $DescrizioneHotelLang): 

			$newDescrizioneHotelLang = new DescrizioneHotelLingua();
			$newDescrizioneHotelLang->master_id = $newDescrizioneHotel->id;
			$newDescrizioneHotelLang->lang_id = $DescrizioneHotelLang->lang_id;
			$newDescrizioneHotelLang->testo = $DescrizioneHotelLang->testo;
			$newDescrizioneHotelLang->save();

		endforeach;

		return response()->json(['success' => 'success', 'id' => $newDescrizioneHotel->id], 200);
		

	}

}
