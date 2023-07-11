<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDateAndMaxDayTassaSoggiorno extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblTassaSoggiorno', function (Blueprint $table) {
			
			$table->boolean("validita_data");
            $table->date("data_iniziale");
            $table->date("data_finale");
            $table->integer("max_giorni");
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblTassaSoggiorno', function (Blueprint $table) {
            
			$table->dropColumn("validita_data");
			$table->dropColumn("data_iniziale");
			$table->dropColumn("data_finale");
			$table->dropColumn("max_giorni");
			
        });
    }
}
