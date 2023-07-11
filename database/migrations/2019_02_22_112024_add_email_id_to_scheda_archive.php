<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEmailIdToSchedaArchive extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
            $table->integer("email_id")->default(0)->after('hotel_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblMailSchedaArchive', function (Blueprint $table) {
            $table->dropColumn('email_id');
        });
    }
}
