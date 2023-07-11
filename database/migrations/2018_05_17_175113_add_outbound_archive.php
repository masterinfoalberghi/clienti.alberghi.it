<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOutboundArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
	    Schema::create('tblStatsHotelOutboundLinkArchive', function (Blueprint $table) {
			$table->engine = 'MyISAM';
            $table->increments('id');
			$table->integer("hotel_id");
			$table->string("user_agent");
            $table->timestamps();
        });
		
		Schema::table('tblStatsHotelOutboundLinkRead', function (Blueprint $table) {
            $table->string('giorno')->after("mese"); 
			$this->info('Aggiungere a mano l\'indice sul campo giorno nella tabella tblStatsHotelRead');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblStatsHotelOutboundLinkArchive');
    }
}
