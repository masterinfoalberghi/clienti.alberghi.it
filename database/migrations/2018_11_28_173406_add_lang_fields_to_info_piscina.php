<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddLangFieldsToInfoPiscina extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblInfoPiscina', function (Blueprint $table) {
             DB::statement('ALTER TABLE tblInfoPiscina CHANGE suggerimento_posizione suggerimento_posizione_it VARCHAR(255)');
             $table->string("suggerimento_posizione_en")->after("suggerimento_posizione_it");
             $table->string("suggerimento_posizione_fr")->after("suggerimento_posizione_en");
             $table->string("suggerimento_posizione_de")->after("suggerimento_posizione_fr");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblInfoPiscina', function (Blueprint $table) {
            DB::statement('ALTER TABLE tblInfoPiscina CHANGE suggerimento_posizione_it suggerimento_posizione VARCHAR(255)');
            $table->dropColumn(['suggerimento_posizione_en','suggerimento_posizione_fr','suggerimento_posizione_de']);
        });
    }
}
