<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblParoleChiaveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblParoleChiave', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chiave', 255);
            $table->tinyInteger('ricerca_mappa')->default(0);
            $table->string('nome_it', 255)->default('');
            $table->string('nome_en', 255)->default('');
            $table->string('nome_fr', 255)->default('');
            $table->string('nome_de', 255)->default('');
            $table->integer('order_ricerca_mappa')->default(0);
            $table->date('mappa_dal')->nullable();
            $table->date('mappa_al')->nullable();
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
        Schema::dropIfExists('tblParoleChiave');
    }
}
