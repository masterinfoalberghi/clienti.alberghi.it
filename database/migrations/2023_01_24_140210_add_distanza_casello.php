<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanzaCasello extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblLocalita', function (Blueprint $table) {
            $table->json("distanza_casello")->after("staz_coordinate_note")->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblLocalita', function (Blueprint $table) {
            $table->dropColumn("distanza_casello");
        });
    }
}
