<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;



class CreatePaginaPreferitiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

     $pagina_preferiti = array(
				"lang_id" => "it",
				"attiva" => 1,
				"uri" => "preferiti",
				"template" => "listing",
				"menu_riviera_romagnola" => 1,
				"vetrine_top_enabled" => 0,
				"seo_title" => "I tuoi preferiti",
				"seo_description" => "I tuoi preferiti",
				"h1" => "I tuoi preferiti",
				"h2" => "I tuoi preferiti",
				"descrizione_1" => "I tuoi preferiti",
				"descrizione_2" => "I tuoi preferiti",
				"menu_macrolocalita_id" => 0,
				"menu_localita_id" => 0,
				"vetrina_id" => 0,
				"evidenza_vetrina_id" => 0,
				"banner_vetrina_id" => 0,
				"macrolocalita_count" => 0,
				"ancora" => "I tuoi preferiti",
				"menu_dal" => "2000-01-01",
				"menu_al" => "2000-01-01",
				"menu_auto_annuale" => 0,
				"listing_attivo" => 1,
				"listing_count" => 27,
				"listing_macrolocalita_id" => 11,
				"listing_localita_id" => 49,
				"indirizzo_stradario" => "",
				"localita_id_stradario" => NULL,
				"macrolocalita_id_stradario" => NULL,
				"punto_di_forza" => "",
				"listing_puntoForzaChiave_id" => 0,
				"listing_parolaChiave_id" => 0,
				"listing_offerta" => "",
				"listing_offerta_prenota_prima" => "",
				"listing_gruppo_servizi_id" => 0,
				"listing_categorie" => "",
				"listing_tipologie" => "",
				"listing_trattamento" => "",
				"listing_coupon" => 0,
				"listing_bambini_gratis" => 0,
				"listing_whatsapp" => 0,
				"listing_green_booking" => 0,
				"listing_eco_sostenibile" => 0,
				"listing_annuali" => 0,
				"listing_preferiti" => 1,
			);


      DB::table('tblCmsPagine')->insert($pagina_preferiti);


    }
}
