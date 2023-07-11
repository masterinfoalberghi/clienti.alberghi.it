<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblServiziPrivatiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblServiziPrivati', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id');
            $table->string('nome', 255);
            $table->integer('categoria_id')->default(0);
            $table->timestamps();
            $table->index('hotel_id', 'tblserviziprivati_hotel_id_index');
            
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
        Schema::dropIfExists('tblServiziPrivati');
    }
}
