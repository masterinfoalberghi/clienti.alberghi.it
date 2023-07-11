<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class HidePriceList extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Schema::table('tblHotel', function (Blueprint $table) {
        //     $table->boolean("hide_price_list")->default(false);
        // });
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        // Schema::table('tblHotel', function (Blueprint $table) {
        //     $table->dropColumn("hide_price_list");
        // });

    }
}
