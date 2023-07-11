<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnListingSuiteToCmsPagine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
            $table->boolean("listing_suite")->after('listing_annuali')->defualt(0);
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
            $table->dropColumn('listing_suite');
        });
    }
}
