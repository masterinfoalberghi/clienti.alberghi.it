<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMetodiPagamentoHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->boolean('bancomat')->default(false)->after('paypal');
            $table->string('note_bancomat_it')->nullable()->default('')->after('bancomat');
            $table->string("note_bancomat_en")->nullable()->default('')->after("note_bancomat_it");
            $table->string("note_bancomat_fr")->nullable()->default('')->after("note_bancomat_en");
            $table->string("note_bancomat_de")->nullable()->default('')->after("note_bancomat_fr");
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
            $table->dropColumn(['bancomat','note_bancomat_it', "note_bancomat_en", "note_bancomat_fr", "note_bancomat_de"]);
        });
    }
}
