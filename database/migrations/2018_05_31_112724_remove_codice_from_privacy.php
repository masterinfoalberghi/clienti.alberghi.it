<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveCodiceFromPrivacy extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblAcceptancePrivacy', function (Blueprint $table) {
            $table->dropColumn("codice_cookie");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblAcceptancePrivacy', function (Blueprint $table) {
            $table->string("codice_cookie", 100);
        });
    }
}
