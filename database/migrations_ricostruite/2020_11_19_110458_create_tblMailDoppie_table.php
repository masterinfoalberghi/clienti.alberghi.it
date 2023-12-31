<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblMailDoppieTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMailDoppie', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ids_send_mail', 500);
            $table->string('codice_cookie', 255);
            $table->string('prefill', 1000)->default('');
            $table->timestamps();
            $table->index(['codice_cookie', 'ids_send_mail'], 'tblmaildoppie_codice_cookie_ids_send_mail_index');
            
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
        Schema::dropIfExists('tblMailDoppie');
    }
}
