<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsHotelOutboundLinkReadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsHotelOutboundLinkRead', function (Blueprint $table) {
            $table->smallInteger('anno');
            $table->tinyInteger('mese');
            $table->string('giorno', 255);
            $table->integer('hotel_id');
            $table->integer('visits');
            $table->primary(['anno', 'mese', 'giorno', 'hotel_id']);
            
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
        Schema::dropIfExists('tblStatsHotelOutboundLinkRead');
    }
}