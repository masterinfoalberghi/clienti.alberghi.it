<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumCompiutiToBgTop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblVetrineBambiniGratisTop', function (Blueprint $table) {
            $table->enum('anni_compiuti',['compiuti','non compiuti'])->default('compiuti')->after('fino_a_anni');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblVetrineBambiniGratisTop', function (Blueprint $table) {
            $table->dropColumn('anni_compiuti');
        });
    }
}
