<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblOffertePrenotaPrimaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblOffertePrenotaPrima', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('hotel_id');
            $table->date('valido_dal');
            $table->date('valido_al');
            $table->date('prenota_entro');
            $table->integer('perc_sconto')->default(0);
            $table->integer('per_persone')->default(0);
            $table->integer('per_giorni')->default(0);
            $table->tinyInteger('attivo')->default(1);
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index('hotel_id', 'tblofferteprenotaprima_hotel_id_index');
            
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
        Schema::dropIfExists('tblOffertePrenotaPrima');
    }
}