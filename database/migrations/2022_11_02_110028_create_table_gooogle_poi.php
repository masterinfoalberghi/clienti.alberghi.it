<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGoooglePoi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblGooglePoi', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_id')->nullable()->default(0);
            $table->string('type')->nullable()->default(null);
            $table->string('name')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('end_address')->nullable()->default(null);
            $table->string('lat')->nullable()->default(null);
            $table->string('lng')->nullable()->default(null);
            $table->string('place_id')->nullable()->default(null);
            $table->string('google_types')->nullable()->default(null);
            $table->string('distances')->nullable()->default(null);
            $table->text('google_results')->nullable()->default(null);
            $table->text('google_routes')->nullable()->default(null);
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrentOnUpdate();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblGooglePoi');
    }
}
