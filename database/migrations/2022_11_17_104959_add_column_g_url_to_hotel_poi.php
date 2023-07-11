<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnGUrlToHotelPoi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotelPoi', function (Blueprint $table) {
            $table->string('g_url')->nullable()->default(null)->after('g_modo');
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
            $table->dropColumn('g_url');
        });
    }
}
