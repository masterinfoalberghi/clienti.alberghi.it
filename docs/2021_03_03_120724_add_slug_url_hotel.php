<?php

use App\Hotel;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

class AddSlugUrlHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->string('slug_url')->nullable()->default('')->after('nome');
        });

        $hotels = Hotel::all();

        foreach ($hotels as $h) {
            $h->slug_url = $h->id .'-'.Str::slug($h->nome, '-');
            $h->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->dropColumn('slug_url');
        });
    }
}
