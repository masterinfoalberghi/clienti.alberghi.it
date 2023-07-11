<?php

namespace Database\Seeders;

use App\MailMultipla;
use Illuminate\Database\Seeder;

class MailMultiplaSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $mm = MailMultipla::where('api_sync', false)->update(['api_sync' => true]);
    }
}
