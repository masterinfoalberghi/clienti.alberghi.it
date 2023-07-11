<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPeculiaritaBenessereLang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblInfoBenessere', function (Blueprint $table) {
            $table->string('peculiarita_en')->nullable()->default('')->after('peculiarita');
            $table->string('peculiarita_fr')->nullable()->default('')->after('peculiarita_en');
            $table->string('peculiarita_de')->nullable()->default('')->after('peculiarita_fr');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblInfoBenessere', function (Blueprint $table) {
            $table->dropColumn(['peculiarita_en', 'peculiarita_fr', 'peculiarita_de']);
        });
    }
}
