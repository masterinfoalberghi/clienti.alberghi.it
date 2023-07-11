<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteShareTablesFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {	
				
        Schema::table('tblStatsHotelShareArchive', function (Blueprint $table) {
            $table->dropColumn("os");
			$table->dropColumn("IP");
			$table->dropColumn("useragent");
        });
				
		Schema::table('tblStatsHotelShareRead', function (Blueprint $table) {
			$table->dropColumn("anno");
			$table->dropColumn("mese");
			$table->dropColumn("giorno");
		});
				
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblStatsHotelShareArchive', function (Blueprint $table) {
			
			$table->string("useragent")->after("roi");
			$table->string("IP")->after("useragent");
			$table->string("os")->after("IP");
			
        });
		
		Schema::table('tblStatsHotelShareRead', function (Blueprint $table) {
			$table->integer("anno");
			$table->integer("mese");
			$table->integer("giorno");
		});
    }
}
