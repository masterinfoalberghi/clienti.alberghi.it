<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblMailSchedaReadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMailSchedaRead', function (Blueprint $table) {
            $table->string('anno', 4);
            $table->string('mese', 2);
            $table->string('giorno', 2);
            $table->string('tipologia', 10);
            $table->integer('hotel_id');
            $table->integer('conteggio');
            $table->primary(['anno', 'mese', 'giorno', 'tipologia', 'hotel_id']);
            
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
        Schema::dropIfExists('tblMailSchedaRead');
    }
}