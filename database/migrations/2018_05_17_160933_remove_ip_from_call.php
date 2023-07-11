<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIpFromCall extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblStatsHotelCallArchive', function (Blueprint $table) {
            $table->dropColumn("IP");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblStatsHotelCallArchive', function (Blueprint $table) {
            $table->string("IP");
        });
    }
}
