<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderToMacro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblMacrolocalita', function (Blueprint $table) {
            $table->integer('ordering')->nullable()->default(null)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblMacrolocalita', function (Blueprint $table) {
            $table->dropColumn('ordering');
        });
    }
}
