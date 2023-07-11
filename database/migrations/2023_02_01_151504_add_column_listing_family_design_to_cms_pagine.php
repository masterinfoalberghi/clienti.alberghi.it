<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnListingFamilyDesignToCmsPagine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
            $table->boolean("listing_design")->after('listing_suite')->defualt(0);
            $table->boolean("listing_family")->after('listing_design')->defualt(0);
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
            $table->dropColumn(['listing_design', 'listing_family']);
        });
    }
}
