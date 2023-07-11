<?php

namespace Database\Seeders;

use App\MailScheda;
use Illuminate\Database\Seeder;

class MailSchedaSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ms = MailScheda::where('api_sync', false)->update(['api_sync' => true]);
    }
}
