<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailMultiplaStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		
		//Schema::rename('tblStatsHotelMailMultiple', 'tblStatsMailMultipleRead');
		
        Schema::create('tblStatsMailMultipleArchive', function (Blueprint $table) {
			
			$table->engine = 'MyISAM';
			$table->increments('id');
			$table->integer('hotel_id');
			$table->integer('email_id');
			$table->enum('tipologia', ['doppia','mobile','normale','doppia parziale']);
			$table->string('nome');
			$table->string('email');
			$table->text('camere');
			$table->text('richiesta')->nullable();
			$table->datetime('data_invio');
			$table->string('referer')->nullable();
			$table->timestamps();
        });
		
		Schema::create('tblStatsMailMultipleRead', function (Blueprint $table) {
			
			$table->engine = 'MyISAM';
			$table->string('anno',4);
			$table->string('mese',2);
			$table->string('giorno',2);
			$table->string('tipologia',10);
			$table->integer('hotel_id');
			$table->integer('conteggio');
			//$table->primary(['anno', 'mese', 'giorno', 'tipologia', 'hotel_id']);

			echo PHP_EOL . PHP_EOL .("!!!!! ATTENZIONE METTERE A MANO LE CHIAVI PRIMARIE A 'anno', 'mese', 'giorno', 'tipologia', 'hotel_id' SULLA TABELLA tblStatsMailMultipleRead !!!!!") . PHP_EOL . PHP_EOL;
			
        });
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {	
		
        Schema::dropIfExists('tblStatsMailMultipleRead');
        Schema::dropIfExists('tblStatsMailMultipleArchive');
		
    }
}
