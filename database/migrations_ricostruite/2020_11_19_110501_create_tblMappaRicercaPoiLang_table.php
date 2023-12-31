<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblMappaRicercaPoiLangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMappaRicercaPoiLang', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('master_id');
            $table->enum('lang_id', ['it', 'en', 'fr', 'de']);
            $table->string('nome', 255);
            $table->string('info_titolo', 255);
            $table->text('info_desc');
            $table->timestamps();

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
        Schema::dropIfExists('tblMappaRicercaPoiLang');
    }
}
