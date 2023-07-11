<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWhastapp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('tblMailScheda', function (Blueprint $table) {
            $table->string("whatsapp")->nullable()->default(NULL);
        });

         Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
            $table->integer("whatsapp")->nullable()->default(NULL);
        });
         
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('tblMailScheda', function (Blueprint $table) {
            $table->dropColumn("whatsapp");
        });

         Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
            $table->dropColumn("whatsapp");
        });
    }
}
