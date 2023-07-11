<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class GestoreMultiple extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblGestioneMultiple', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('hotel_id');
            $table->integer('contact')->default(1);
            $table->primary(['id', 'hotel_id']);
        });

         DB::statement('ALTER TABLE tblGestioneMultiple MODIFY id INTEGER NOT NULL AUTO_INCREMENT');

        Artisan::call( 'db:seed', [
            '--class' => 'GestioneMultpleSeeder',
            '--force' => true
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblGestioneMultiple');
    }
}
