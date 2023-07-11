<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApplicata extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblTassaSoggiorno', function (Blueprint $table) {
            $table->boolean("applicata")->after("attiva")->default(false);
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
            $table->dropColumn("applicata");
        });
    }
}
