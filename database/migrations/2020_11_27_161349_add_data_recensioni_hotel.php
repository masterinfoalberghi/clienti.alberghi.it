<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataRecensioniHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */

    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->decimal("n_rating_ia")->after("rating_ia")->default(0);
            $table->text("source_rating_ia")->after("n_rating_ia")->nullable()->default(NULL);
            $table->boolean("enabled_rating_ia")->after("source_rating_ia")->default(1);
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
            $table->dropColumn("n_rating_ia");
            $table->dropColumn("enabled_rating_ia");
            $table->dropColumn("source_rating_ia");
        });
    }
}
