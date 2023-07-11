<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoloCon2Adulti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblBambiniGratis', function (Blueprint $table) {
            $table->boolean('solo_2_adulti')->default(false)->nullabble()->after('valido_al');
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
            $table->dropColumn('solo_2_adulti');
        });
    }
}
