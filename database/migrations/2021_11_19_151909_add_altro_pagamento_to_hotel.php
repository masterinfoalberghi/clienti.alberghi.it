<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAltroPagamentoToHotel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->boolean('altro_pagamento')->default(false)->after('note_paypal_de');
            $table->string("note_altro_pagamento_it")->after("altro_pagamento");
            $table->string("note_altro_pagamento_en")->after("note_altro_pagamento_it");
            $table->string("note_altro_pagamento_fr")->after("note_altro_pagamento_en");
            $table->string("note_altro_pagamento_de")->after("note_altro_pagamento_fr");
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
            $table->dropColumn(['altro_pagamento','note_altro_pagamento_it', 'note_altro_pagamento_en', 'note_altro_pagamento_fr', 'note_altro_pagamento_de']);
        });
    }
}
