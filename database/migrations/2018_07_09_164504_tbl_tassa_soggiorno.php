<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TblTassaSoggiorno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblTassaSoggiorno', function (Blueprint $table) {
            $table->increments('id');
			$table->integer("hotel_id");
			$table->boolean("attiva");
			$table->decimal("valore",6,2);
			$table->boolean("bambini_esenti");
			$table->integer("eta_bambini_esenti");
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
        Schema::dropIfExists('tblTassaSoggiorno');
    }
}
