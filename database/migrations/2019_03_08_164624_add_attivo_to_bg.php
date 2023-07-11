<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAttivoToBg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblBambiniGratis', function (Blueprint $table) {
            $table->boolean('attivo')->default(true)->after('data_approvazione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblBambiniGratis', function (Blueprint $table) {
            $table->dropColumn('attivo');
        });
    }
}
