<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToServiziInOut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblServiziInOut', function (Blueprint $table) {
            $table->integer('ordering')->nullable()->default(null)->after('to_fill_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblServiziInOut', function (Blueprint $table) {
            $table->dropColumn('ordering');
        });
    }
}
