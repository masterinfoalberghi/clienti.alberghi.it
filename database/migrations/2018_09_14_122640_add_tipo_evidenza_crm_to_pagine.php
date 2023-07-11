<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTipoEvidenzaCrmToPagine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
          $table->string('tipo_evidenza_crm')->default(null)->after('listing_preferiti');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblCmsPagine', function (Blueprint $table) {
          $table->dropColumn('tipo_evidenza_crm');
        });
    }
}
