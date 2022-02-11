<?php

namespace Database\Seeders;

use App\Models\Dial;
use Illuminate\Database\Seeder;

class DialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $speedDial = Dial::factory()->count(30)->create();
    }
}
