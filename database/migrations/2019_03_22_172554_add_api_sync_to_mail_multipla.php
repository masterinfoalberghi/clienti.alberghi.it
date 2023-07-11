<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiSyncToMailMultipla extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblMailMultiple', function (Blueprint $table) {
            $table->boolean('api_sync')->default(false)->after('referer');
        });

        /*
         * Lego la migration con il suo seed eseguendolo da dentro la migration
         * http://stackoverflow.com/questions/12736120/populating-a-database-in-a-laravel-migration-file
         */
                
        Artisan::call( 'db:seed', [
            '--class' => 'MailMultiplaSyncSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblMailMultiple', function (Blueprint $table) {
            $table->dropColumn('api_sync');
        });
    }
}
