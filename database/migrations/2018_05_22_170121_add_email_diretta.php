<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailDiretta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMailSchedaArchive', function (Blueprint $table) {
			
			$table->engine = 'MyISAM';
            $table->increments('id');
			$table->integer('hotel_id');
			$table->enum('tipologia', ['doppia','mobile','normale','doppia parziale']);
			$table->string('nome');
			$table->string('email');
			$table->string('telefono')->nullable();
			$table->text('camere');
			$table->text('richiesta')->nullable();
			$table->datetime('data_invio');
			$table->string('referer')->nullable();
			$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblMailSchedaArchive');
    }
}
