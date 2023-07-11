<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableServiziAltri extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('tblGruppiServiziInOut', function (Blueprint $table) {
            $table->id();
            $table->string('nome_it')->nullable()->default(null);
            $table->string('nome_en')->nullable()->default(null);
            $table->string('nome_fr')->nullable()->default(null);
            $table->string('nome_de')->nullable()->default(null);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'GruppiServiziAltriSeeder',
            '--force' => true
        ]);

        Schema::create('tblServiziInOut', function (Blueprint $table) {
            $table->id();
            $table->integer('gruppo_id')->nullable()->default(0);
            $table->string('nome_it')->nullable()->default(null);
            $table->string('nome_en')->nullable()->default(null);
            $table->string('nome_fr')->nullable()->default(null);
            $table->string('nome_de')->nullable()->default(null);
            $table->string('options')->nullable()->default(null); // es: gratis|a pagamento; Sì|No
            $table->boolean('to_fill_1')->nullable()->default(false);
            $table->boolean('to_fill_2')->nullable()->default(false);
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => 'ServiziAltriSeeder',
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
        Schema::dropIfExists('tblGruppiServiziInOut');
        
        Schema::dropIfExists('tblServiziInOut');
    }
}
