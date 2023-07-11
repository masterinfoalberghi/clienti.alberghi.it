<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableCategoriePoi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblCategoriePoi', function (Blueprint $table) {
            $table->id();
            $table->string('nome_it')->nullable()->default(null);
            $table->string('nome_en')->nullable()->default(null);
            $table->string('nome_fr')->nullable()->default(null);
            $table->string('nome_de')->nullable()->default(null);
            $table->timestamp('registered_at')->useCurrent();
        });

        Schema::table('tblPoi', function (Blueprint $table) {
            $table->integer('categoria_id')->after('id')->nullable()->default(null);
        });

        Artisan::call('db:seed', [
            '--class' => 'CategoriePoiSeeder',
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
        Schema::dropIfExists('tblCategoriePoi');

        Schema::table('tblPoi', function (Blueprint $table) {
            $table->dropColumn('categoria_id');
        });
    }
}
