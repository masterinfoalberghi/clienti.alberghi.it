<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddListingBonus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
            $table->boolean("listing_bonus_vacanze_2020")->defualt(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
            $table->dropColumn("listing_bonus_vacanze_2020");
        });
    }
}
