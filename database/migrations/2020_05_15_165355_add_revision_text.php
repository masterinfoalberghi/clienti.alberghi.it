<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRevisionText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblDescrizioneHotel', function (Blueprint $table) {
            $table->string("revision_name", 500)->default("Default");
            $table->boolean("online")->default(0);
        });

        DB::table("tblDescrizioneHotel")->update(["online" => 1]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblDescrizioneHotel', function (Blueprint $table) {
            $table->dropColumn("revision_name");
            $table->dropColumn("online");
        });
    }
}
