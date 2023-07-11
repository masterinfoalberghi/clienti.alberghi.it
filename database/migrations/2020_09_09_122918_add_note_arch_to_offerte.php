<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoteArchToOfferte extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblOfferte', function (Blueprint $table) {
            $table->text('note')->nullable()->default(null)->after('attivo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblOfferte', function (Blueprint $table) {
            $table->dropColumn('note');
        });
    }
}
