<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNuoviTrattamenti extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tblHotel', function (Blueprint $table) {
            $table->boolean('trattamento_sd_spiaggia')->nullable()->default(false)->after('trattamento_sd');
            $table->boolean('trattamento_bb_spiaggia')->nullable()->default(false)->after('trattamento_bb');
            $table->boolean('trattamento_mp_spiaggia')->nullable()->default(false)->after('trattamento_mp');
            $table->text('note_ai_it')->nullable()->after('trattamento_sd_spiaggia');
            $table->text('note_ai_en')->nullable()->after('note_ai_it');
            $table->text('note_ai_fr')->nullable()->after('note_ai_en');
            $table->text('note_ai_de')->nullable()->after('note_ai_fr');
            $table->text('note_pc_it')->nullable()->after('note_ai_de');
            $table->text('note_pc_en')->nullable()->after('note_pc_it');
            $table->text('note_pc_fr')->nullable()->after('note_pc_en');
            $table->text('note_pc_de')->nullable()->after('note_pc_fr');
            $table->text('note_mp_it')->nullable()->after('note_pc_de');
            $table->text('note_mp_en')->nullable()->after('note_mp_it');
            $table->text('note_mp_fr')->nullable()->after('note_mp_en');
            $table->text('note_mp_de')->nullable()->after('note_mp_fr');
            $table->text('note_mp_spiaggia_it')->nullable()->after('note_mp_de');
            $table->text('note_mp_spiaggia_en')->nullable()->after('note_mp_spiaggia_it');
            $table->text('note_mp_spiaggia_fr')->nullable()->after('note_mp_spiaggia_en');
            $table->text('note_mp_spiaggia_de')->nullable()->after('note_mp_spiaggia_fr');
            $table->text('note_bb_it')->nullable()->after('note_mp_spiaggia_de');
            $table->text('note_bb_en')->nullable()->after('note_bb_it');
            $table->text('note_bb_fr')->nullable()->after('note_bb_en');
            $table->text('note_bb_de')->nullable()->after('note_bb_fr');
            $table->text('note_bb_spiaggia_it')->nullable()->after('note_bb_de');
            $table->text('note_bb_spiaggia_en')->nullable()->after('note_bb_spiaggia_it');
            $table->text('note_bb_spiaggia_fr')->nullable()->after('note_bb_spiaggia_en');
            $table->text('note_bb_spiaggia_de')->nullable()->after('note_bb_spiaggia_fr');
            $table->text('note_sd_it')->nullable()->after('note_bb_spiaggia_de');
            $table->text('note_sd_en')->nullable()->after('note_sd_it');
            $table->text('note_sd_fr')->nullable()->after('note_sd_en');
            $table->text('note_sd_de')->nullable()->after('note_sd_fr');
            $table->text('note_sd_spiaggia_it')->nullable()->after('note_sd_de');
            $table->text('note_sd_spiaggia_en')->nullable()->after('note_sd_spiaggia_it');
            $table->text('note_sd_spiaggia_fr')->nullable()->after('note_sd_spiaggia_en');
            $table->text('note_sd_spiaggia_de')->nullable()->after('note_sd_spiaggia_fr');
            $table->boolean('trattamenti_moderati')->nullable()->default(null)->after('note_sd_spiaggia_de');
            $table->dateTime('data_moderazione')->nullable()->default(null)->after('trattamenti_moderati');

            
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
            $table->dropColumn(['trattamento_sd_spiaggia',
            'trattamento_bb_spiaggia',
            'trattamento_mp_spiaggia',
            'note_ai_it',
            'note_ai_en',
            'note_ai_fr',
            'note_ai_de',
            'note_pc_it',
            'note_pc_en',
            'note_pc_fr',
            'note_pc_de',
            'note_mp_it',
            'note_mp_en',
            'note_mp_fr',
            'note_mp_de',
            'note_mp_spiaggia_it',
            'note_mp_spiaggia_en',
            'note_mp_spiaggia_fr',
            'note_mp_spiaggia_de',
            'note_bb_it',
            'note_bb_en',
            'note_bb_fr',
            'note_bb_de',
            'note_bb_spiaggia_it',
            'note_bb_spiaggia_en',
            'note_bb_spiaggia_fr',
            'note_bb_spiaggia_de',
            'note_sd_it',
            'note_sd_en',
            'note_sd_fr',
            'note_sd_de',
            'note_sd_spiaggia_it',
            'note_sd_spiaggia_en',
            'note_sd_spiaggia_fr',
            'note_sd_spiaggia_de',
            'trattamenti_moderati',
            'data_moderazione'
            ]);
        });
    }
}
