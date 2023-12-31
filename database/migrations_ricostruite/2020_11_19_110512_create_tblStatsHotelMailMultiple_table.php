<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsHotelMailMultipleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsHotelMailMultiple', function (Blueprint $table) {
            $table->integer('hotel_id');
            $table->integer('n_mail');
            $table->mediumInteger('giorno');
            $table->mediumInteger('mese');
            $table->mediumInteger('anno');
            $table->enum('tipologia', ['normale', 'wishlist', 'mobile'])->default('normale');
            $table->timestamps();
            $table->primary(['hotel_id', 'tipologia', 'giorno', 'mese', 'anno'], 'stats_hotel_id_tipologia_giorno_mese_anno_primary');
            $table->index('tipologia', 'tblstatshotelmailmultiple_tipologia_index');
            
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
        Schema::dropIfExists('tblStatsHotelMailMultiple');
    }
}
