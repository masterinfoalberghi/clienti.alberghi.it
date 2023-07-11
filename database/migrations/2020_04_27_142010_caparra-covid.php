<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CaparraCovid extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblCaparre', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("hotel_id");
            // $table->string("ip", 15);
            $table->integer("option");
            $table->date("from")->nullable();
            $table->date("to")->nullable();
            $table->integer("day_before")->nullable();
            $table->decimal("perc")->nullable();
            $table->integer("month_after")->nullable();
            $table->boolean("enabled")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblCaparre');
    }
}
