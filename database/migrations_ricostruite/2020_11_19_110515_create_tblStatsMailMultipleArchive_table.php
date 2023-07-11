<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsMailMultipleArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsMailMultipleArchive', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('hotel_id');
            $table->integer('email_id');
            $table->enum('lang_id', ['it', 'en', 'fr', 'de'])->nullable();
            $table->enum('tipologia', ['doppia', 'mobile', 'normale', 'doppia parziale', 'wishlist']);
            $table->string('nome', 255);
            $table->string('email', 255);
            $table->text('camere');
            $table->text('richiesta')->nullable();
            $table->dateTime('data_invio');
            $table->string('referer', 255)->nullable();
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
        Schema::dropIfExists('tblStatsMailMultipleArchive');
    }
}
