<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLangToLabelCaparre extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblLabelCaparre', function (Blueprint $table) {
            DB::statement('ALTER TABLE tblLabelCaparre CHANGE testo testo_it TEXT');
            $table->text('testo_en')->nullable()->default('')->after('testo_it');
            $table->text('testo_fr')->nullable()->default('')->after('testo_en');
            $table->text('testo_de')->nullable()->default('')->after('testo_fr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblLabelCaparre', function (Blueprint $table) {
            DB::statement('ALTER TABLE tblLabelCaparre CHANGE testo_it testo TEXT');
            $table->dropColumn(['testo_en', 'testo_fr', 'testo_de']);
        });
    }
}
