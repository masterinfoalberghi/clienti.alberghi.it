<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailDiretta2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMailSchedaRead', function (Blueprint $table) {
			
			$table->engine = 'MyISAM';
			$table->string('anno',4);
			$table->string('mese',2);
			$table->string('giorno',2);
			$table->string('tipologia',10);
			$table->integer('hotel_id');
			$table->integer('conteggio');
			$table->primary(['anno', 'mese', 'giorno', 'tipologia', 'hotel_id']);
			
        });
		
		
		
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblMailSchedaRead');
    }
}
