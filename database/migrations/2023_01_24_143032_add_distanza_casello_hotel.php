<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanzaCaselloHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->decimal("distanza_casello", 10)->after("distanza_fiera")->default(0);
            $table->string("distanza_casello_label", 100)->after("distanza_casello")->default(NULL);
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
            $table->dropColumn("distanza_casello");
            $table->dropColumn("distanza_casello_label");
        });
    }
}
