<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToHotelPoiNumericValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotelPoi', function (Blueprint $table) {
            $table->integer('g_distanza_numeric')->nullable()->default(null)->after('g_distanza');
            $table->integer('g_durata_numeric')->nullable()->default(null)->after('g_durata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblHotelPoi', function (Blueprint $table) {
            $table->dropColumn(['g_distanza_numeric', 'g_durata_numeric']);
        });
    }
}
