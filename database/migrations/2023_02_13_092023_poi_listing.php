<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoiListing extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
            $table->integer("listing_poi_id")->after("listing_offerta")->default(NULL)->nullable();
            $table->integer("listing_tipologia_id")->after("listing_poi_id")->default(NULL)->nullable();
            $table->decimal("listing_poi_dist",10,2)->after("listing_tipologia_id")->default(NULL)->nullable();
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
            $table->dropColumn("listing_poi_id");
            $table->dropColumn("listing_tipologia_id");
            $table->dropColumn("listing_poi_dist");
        });
    }
}
