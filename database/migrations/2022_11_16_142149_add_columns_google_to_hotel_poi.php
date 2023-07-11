<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsGoogleToHotelPoi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotelPoi', function (Blueprint $table) {
            $table->string('g_distanza')->nullable()->default(null)->after('distanza');
            $table->string('g_durata')->nullable()->default(null)->after('g_distanza');
            $table->string('g_descrizione_rotta')->nullable()->default(null)->after('g_durata');
            $table->string('g_modo')->nullable()->default(null)->after('g_descrizione_rotta');
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
            $table->dropColumn(['g_distanza','g_durata', 'g_descrizione_rotta', 'g_modo']);
        });
    }
}
