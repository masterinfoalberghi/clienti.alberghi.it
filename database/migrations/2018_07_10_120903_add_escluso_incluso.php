<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEsclusoIncluso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblTassaSoggiorno', function (Blueprint $table) {
            $table->boolean("inclusa")->after("attiva");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblTassaSoggiorno', function (Blueprint $table) {
            $table->dropColumn("inclusa");
        });
    }
}
