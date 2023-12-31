<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblUtenteCouponGeneratiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblUtenteCouponGenerati', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('couponGenerati_id')->default(0);
            $table->string('email', 150)->default('');
            $table->tinyInteger('consegnato')->default(0);
            $table->date('data_consegna')->default('0000-00-00');
            $table->text('note_consegna');
            $table->timestamps();
            $table->index('couponGenerati_id', 'tblutentecoupongenerati_coupongenerati_id_index');
            
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblUtenteCouponGenerati');
    }
}
