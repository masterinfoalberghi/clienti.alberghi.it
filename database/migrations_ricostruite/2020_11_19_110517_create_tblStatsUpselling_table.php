<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsUpsellingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsUpselling', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('queue_id');
            $table->unsignedInteger('hotel_id');
            $table->timestamps();
            $table->index('hotel_id', 'tblstatsupselling_hotel_id_index');
            $table->index('queue_id', 'tblstatsupselling_queue_id_index');
            
            $table->charset = 'utf8';
            $table->collation = 'utf8_unicode_ci';
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblStatsUpselling');
    }
}
