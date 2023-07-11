<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAltroTrattamentoToHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->text('altro_trattamento_it')->nullable()->after('note_altro_de');
            $table->text('altro_trattamento_en')->nullable()->after('altro_trattamento_it');
            $table->text('altro_trattamento_fr')->nullable()->after('altro_trattamento_en');
            $table->text('altro_trattamento_de')->nullable()->after('altro_trattamento_fr');
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
            $table->dropColumn(['altro_trattamento_it', 'altro_trattamento_en', 'altro_trattamento_fr', 'altro_trattamento_de']);
        });
    }
}
