<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiSyncToMailScheda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblMailScheda', function (Blueprint $table) {
            $table->boolean('api_sync')->default(false);
        });

        /*
         * Lego la migration con il suo seed eseguendolo da dentro la migration
         * http://stackoverflow.com/questions/12736120/populating-a-database-in-a-laravel-migration-file
         */
                
        Artisan::call( 'db:seed', [
            '--class' => 'MailSchedaSyncSeeder',
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
        Schema::table('tblMailScheda', function (Blueprint $table) {
            $table->dropColumn('api_sync');
        });
    }
}
