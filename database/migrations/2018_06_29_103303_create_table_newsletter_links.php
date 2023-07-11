<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNewsletterLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblNewsletterLinks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('titolo')->default("");
            $table->date('data_invio')->nullable()->default(null);
            $table->string('url')->default("");
            $table->text('note')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblNewsletterLinks');
    }
}
