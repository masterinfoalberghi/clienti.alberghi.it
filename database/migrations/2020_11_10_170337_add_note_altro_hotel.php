<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoteAltroHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->text('note_altro_it')->nullable()->after('note_sd_spiaggia_de');
            $table->text('note_altro_en')->nullable()->after('note_altro_it');
            $table->text('note_altro_fr')->nullable()->after('note_altro_en');
            $table->text('note_altro_de')->nullable()->after('note_altro_fr');
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
            $table->dropColumn(['note_altro_it', 'note_altro_en', 'note_altro_fr', 'note_altro_de']);
        });
    }
}
