<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsCresimeToHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->boolean('organizzazione_comunioni')->default(false)->after('tmp_punti_di_forza_slug_de');
            $table->boolean('organizzazione_cresime')->default(false)->after('organizzazione_comunioni');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->dropColumn(['organizzazione_comunioni', 'organizzazione_cresime']);
        });
    }
}
