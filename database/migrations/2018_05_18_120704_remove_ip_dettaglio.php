<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIpDettaglio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblStatsHotelArchive', function (Blueprint $table) {
            $table->dropColumn("IP");
        });
		
		Schema::table('tblStatsHotelRead', function (Blueprint $table) {
            $table->string("giorno")->after("mese");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    
    public function down()
    {
        Schema::table('tblStatsHotelArchive', function (Blueprint $table) {
            $table->string("IP");
        });
		
		Schema::table('tblStatsHotelRead', function (Blueprint $table) {
            $table->dropColumn("giorno");
        });
		
    }
}
