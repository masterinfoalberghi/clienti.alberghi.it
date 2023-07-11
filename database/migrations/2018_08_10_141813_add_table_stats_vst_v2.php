<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableStatsVstV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsVstReadV2', function (Blueprint $table) {
			$table->date("created_at");
			$table->integer("pagina_id");
			$table->integer("hotel_id");
			$table->integer("visits");
			$table->enum("device", ['desktop', 'tablet', 'phone']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblStatsVstReadV2');
    }
}
