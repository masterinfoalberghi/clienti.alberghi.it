<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMotivazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tblMotivazioni', function (Blueprint $table) {
            $table->increments('id');
            $table->string('motivazione')->nullable()->defalut(null);
            $table->timestamps();
        });
        DB::table('tblMotivazioni')->insert([
            ['motivazione' => "La promozione in oggeto è uguale a un'altra online"],
            ['motivazione' => "Il prezzo al giorno a persona non può essere considerato veritiero"],
            ['motivazione' => "Il prezzo al giorno a persona non corrisponde ai costi indicati nel testo"],
            ['motivazione' => "La promozione è stata spostata in un'altra categoria"],
            ['motivazione' => "Le date di validità della promozione non sono veritiere e/o non corrispondono a quanto descritto nel testo"],
            ['motivazione' => "Il prezzo indicato si riferisce a un servizio e non al costo del soggiorno"],
            ['motivazione' => "Il testo della promozione è stato riformattato per favorirne leggibilità e comprensione"],
            ['motivazione' => "Le date di validità della promozione sono state modificate"],
            ['motivazione' => "Sono stati cancellati numero di telefono e/o indirizzo mail e/o riferimenti diversi da Info Alberghi"],
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tblMotivazioni');
    }
}
