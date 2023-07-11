<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteWaLangToHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->text('notewa_it')->after('whatsapp')->nullable()->default('');
            $table->text('notewa_en')->after('notewa_it')->nullable()->default('');
            $table->text('notewa_fr')->after('notewa_en')->nullable()->default('');
            $table->text('notewa_de')->after('notewa_fr')->nullable()->default('');
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
            $table->dropColumn(['notewa_it', 'notewa_en', 'notewa_fr', 'notewa_de']);
        });
    }
}
