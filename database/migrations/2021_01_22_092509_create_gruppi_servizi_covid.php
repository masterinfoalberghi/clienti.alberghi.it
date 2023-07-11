<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGruppiServiziCovid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblGruppiServiziCovid', function (Blueprint $table) {
            $table->id();
            $table->string('nome_it')->nullable()->default(null);
            $table->string('nome_en')->nullable()->default(null);
            $table->string('nome_fr')->nullable()->default(null);
            $table->string('nome_de')->nullable()->default(null);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'GruppiServiziCovidSeeder',
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
        Schema::dropIfExists('tblGruppiServiziCovid');
    }
}
