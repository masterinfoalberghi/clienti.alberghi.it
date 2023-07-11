<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablePrice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsPriceV2', function (Blueprint $table) {
            $table->increments('id');
			$table->string('anno',4);
			$table->string('mese',2);
			$table->decimal("price",8,2);
			$table->enum("offer", ["offerta", "lastminute", "prenotaprima", "bambinigratis"]);
			$table->integer("localita_id");
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
        Schema::dropIfExists('tblStatsPriceV2');
    }
}
