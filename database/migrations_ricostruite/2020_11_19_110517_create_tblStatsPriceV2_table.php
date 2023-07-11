<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsPriceV2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsPriceV2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('anno', 4);
            $table->string('mese', 2);
            $table->decimal('price', 8, 2);
            $table->enum('offer', ['offerta', 'lastminute', 'prenotaprima', 'bambinigratis']);
            $table->integer('localita_id');
            $table->nullableTimestamps();

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
        Schema::dropIfExists('tblStatsPriceV2');
    }
}
