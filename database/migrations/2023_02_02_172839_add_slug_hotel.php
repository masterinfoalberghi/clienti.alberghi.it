<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->string("slug_it", 500)->nullable()->default(NULL)->after("nome");
            $table->string("slug_en", 500)->nullable()->default(NULL)->after("slug_it");
            $table->string("slug_fr", 500)->nullable()->default(NULL)->after("slug_en");
            $table->string("slug_de", 500)->nullable()->default(NULL)->after("slug_fr");
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
            $table->dropColumn("slug_it");
            $table->dropColumn("slug_en");
            $table->dropColumn("slug_fr");
            $table->dropColumn("slug_de");
        });
    }
}
