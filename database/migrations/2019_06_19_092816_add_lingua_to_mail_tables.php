<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLinguaToMailTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         * Commentata perchè ci vuole troppo per rifarla
         * quindi in un eventuale rollback bisogna gestirla a mano con Gianluca
         */
        
        // Schema::table('tblMailScheda', function (Blueprint $table) {
        //     $table->enum("lang_id", ['it', 'en', 'fr', 'de'])->nullable()->default(null)->after('hotel_id');
        // });

        // Schema::table('tblMailMultiple', function (Blueprint $table) {
        //     $table->enum("lang_id", ['it', 'en', 'fr', 'de'])->nullable()->default(null)->after('id');
        // });

        // Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
        //     $table->enum("lang_id", ['it', 'en', 'fr', 'de'])->nullable()->default(null)->after('email_id');
        // });

        // Schema::table('tblStatsMailMultipleArchive', function (Blueprint $table) {
        //     $table->enum("lang_id", ['it', 'en', 'fr', 'de'])->nullable()->default(null)->after('email_id');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('tblMailScheda', function (Blueprint $table) {
        //     $table->dropColumn(["lang_id"]);
        // });
        // Schema::table('tblMailMultiple', function (Blueprint $table) {
        //     $table->dropColumn(["lang_id"]);
        // });
        // Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
        //     $table->dropColumn(["lang_id"]);
        // });
        // Schema::table('tblStatsMailMultipleArchive', function (Blueprint $table) {
        //     $table->dropColumn(["lang_id"]);
        // });
    }
}
