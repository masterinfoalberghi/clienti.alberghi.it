<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Migration auto-generated by Sequel Pro Laravel Export (1.7.0)
 * @see https://github.com/cviebrock/sequel-pro-laravel-export
 */
class CreateTblStatsVttArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblStatsVttArchive', function (Blueprint $table) {
            $table->unsignedInteger('pagina_id');
            $table->unsignedInteger('hotel_id');
            $table->string('referer', 255)->nullable()->charset('utf8')->collation('utf8_unicode_ci');
            $table->string('useragent', 255)->charset('utf8')->collation('utf8_unicode_ci');
            $table->dateTime('created_at');

            $table->charset = 'latin1';
            $table->collation = 'latin1_swedish_ci';
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblStatsVttArchive');
    }
}
