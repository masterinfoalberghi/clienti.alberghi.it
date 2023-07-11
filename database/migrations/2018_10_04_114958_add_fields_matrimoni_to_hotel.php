<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsMatrimoniToHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->boolean('organizzazione_matrimoni')->default(false)->after('tmp_punti_di_forza_slug_de');
            $table->text('note_organizzazione_matrimoni')->after('organizzazione_matrimoni');
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
            $table->dropColumn(['organizzazione_matrimoni', 'note_organizzazione_matrimoni']);
        });
    }
}
