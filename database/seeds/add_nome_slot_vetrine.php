<?php


use Illuminate\Database\Seeder;
use App\Hotel;
use App\SlotVetrina;

class add_nome_slot_vetrine extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
		$hotel = Hotel::all();
		
		foreach($hotel as $h):
		
			echo $h->id;			
				SlotVetrina::where("hotel_id", $h->id)->update(['hotel_nome' => $h->nome, 'hotel_categoria_id' => $h->categoria_id, 'hotel_prezzo_min' => $h->prezzo_min, 'hotel_prezzo_max' => $h->prezzo_max]);
			echo "\n"; 
			
		endforeach;
       
    }
}
