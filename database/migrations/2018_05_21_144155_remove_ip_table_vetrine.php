<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveIpTableVetrine extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		foreach (['tblStatsVetrine', 'tblStatsVaat', 'tblStatsVot', 'tblStatsVst', 'tblStatsVtt'] as $tbl) {
		
			Schema::table($tbl . "Archive", function (Blueprint $table) {
				$table->dropColumn("IP");
			});
		
		}
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		foreach (['tblStatsVetrine', 'tblStatsVaat', 'tblStatsVot', 'tblStatsVst', 'tblStatsVtt'] as $tbl) {
		
			Schema::table($tbl . "Archive", function (Blueprint $table) {
				$table->string("IP");
			});
		
		}
		
	}
}
