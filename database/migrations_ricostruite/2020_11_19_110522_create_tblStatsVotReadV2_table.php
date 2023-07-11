<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsVotReadV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsVotReadV2', function (Blueprint $table) {
            $table->date('created_at');
            $table->integer('pagina_id');
            $table->integer('hotel_id');
            $table->integer('visits');
            $table->enum('device', ['desktop', 'tablet', 'phone']);
            $table->primary(['created_at', 'pagina_id', 'hotel_id', 'device']);
            
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
        Schema::dropIfExists('tblStatsVotReadV2');
    }
}
