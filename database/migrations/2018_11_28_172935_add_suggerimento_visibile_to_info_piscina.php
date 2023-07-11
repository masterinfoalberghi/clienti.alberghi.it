<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSuggerimentoVisibileToInfoPiscina extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblInfoPiscina', function (Blueprint $table) {
            $table->boolean('suggerimento_visibile')->default(false)->after('suggerimento_posizione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblInfoPiscina', function (Blueprint $table) {
            $table->dropColumn('suggerimento_visibile');
        });
    }
}
