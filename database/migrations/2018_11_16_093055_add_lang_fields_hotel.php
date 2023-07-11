<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLangFieldsHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            DB::statement('ALTER TABLE tblHotel CHANGE aperto_altro aperto_altro_it VARCHAR(100)');
            DB::statement('ALTER TABLE tblHotel CHANGE checkin_da checkin_it TEXT');
            DB::statement('ALTER TABLE tblHotel CHANGE checkout_a checkout_it TEXT');

            DB::statement('ALTER TABLE tblHotel CHANGE caparra_altro caparra_altro_it VARCHAR(100)');


            $table->string("aperto_altro_en")->after("aperto_altro_it");
            $table->string("aperto_altro_fr")->after("aperto_altro_en");
            $table->string("aperto_altro_de")->after("aperto_altro_fr");

            $table->text('checkin_en')->after('checkout_it');
            $table->text('checkout_en')->after('checkin_en');
            $table->text('checkin_fr')->after('checkout_en');
            $table->text('checkout_fr')->after('checkin_fr');
            $table->text('checkin_de')->after('checkout_fr');
            $table->text('checkout_de')->after('checkin_de');

            $table->string("caparra_altro_en")->after("caparra_altro_it");
            $table->string("caparra_altro_fr")->after("caparra_altro_en");
            $table->string("caparra_altro_de")->after("caparra_altro_fr");


            DB::statement('ALTER TABLE tblHotel CHANGE note_contanti note_contanti_it VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_assegno note_assegno_it VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_carta_credito note_carta_credito_it VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_bonifico note_bonifico_it VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_paypal note_paypal_it VARCHAR(255)');

            $table->string("note_contanti_en")->after("note_contanti_it");
            $table->string("note_contanti_fr")->after("note_contanti_en");
            $table->string("note_contanti_de")->after("note_contanti_fr");

            $table->string("note_assegno_en")->after("note_assegno_it");
            $table->string("note_assegno_fr")->after("note_assegno_en");
            $table->string("note_assegno_de")->after("note_assegno_fr");

            $table->string("note_carta_credito_en")->after("note_carta_credito_it");
            $table->string("note_carta_credito_fr")->after("note_carta_credito_en");
            $table->string("note_carta_credito_de")->after("note_carta_credito_fr");

            $table->string("note_bonifico_en")->after("note_bonifico_it");
            $table->string("note_bonifico_fr")->after("note_bonifico_en");
            $table->string("note_bonifico_de")->after("note_bonifico_fr");

            $table->string("note_paypal_en")->after("note_paypal_it");
            $table->string("note_paypal_fr")->after("note_paypal_en");
            $table->string("note_paypal_de")->after("note_paypal_fr");

            DB::statement('ALTER TABLE tblHotel CHANGE altra_lingua altra_lingua_it VARCHAR(255)');
            $table->string("altra_lingua_en")->after("altra_lingua_it");
            $table->string("altra_lingua_fr")->after("altra_lingua_en");
            $table->string("altra_lingua_de")->after("altra_lingua_fr");

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            DB::statement('ALTER TABLE tblHotel CHANGE aperto_altro_it aperto_altro VARCHAR(100)');
            $table->dropColumn(['aperto_altro_en','aperto_altro_fr','aperto_altro_de']);

            DB::statement('ALTER TABLE tblHotel CHANGE checkin_it checkin_da TEXT');
            DB::statement('ALTER TABLE tblHotel CHANGE checkout_it checkout_a TEXT');
            $table->dropColumn(['checkin_en','checkout_en','checkin_fr','checkout_fr','checkin_de','checkout_de']);

            DB::statement('ALTER TABLE tblHotel CHANGE caparra_altro_it caparra_altro VARCHAR(100)');
            $table->dropColumn(['caparra_altro_en','caparra_altro_fr','caparra_altro_de']);

            DB::statement('ALTER TABLE tblHotel CHANGE note_contanti_it note_contanti VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_assegno_it note_assegno VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_carta_credito_it note_carta_credito VARCHAR(255)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_bonifico_it note_bonifico VARCHAR(20)');
            DB::statement('ALTER TABLE tblHotel CHANGE note_paypal_it note_paypal VARCHAR(255)');

            $table->dropColumn(['note_contanti_en','note_contanti_fr','note_contanti_de','note_assegno_en','note_assegno_fr','note_assegno_de','note_carta_credito_en','note_carta_credito_fr','note_carta_credito_de','note_bonifico_en','note_bonifico_fr','note_bonifico_de','note_paypal_en','note_paypal_fr','note_paypal_de']);


            DB::statement('ALTER TABLE tblHotel CHANGE altra_lingua_it altra_lingua VARCHAR(255)');

            $table->dropColumn(['altra_lingua_en','altra_lingua_fr','altra_lingua_de']);


        });
    }
}
