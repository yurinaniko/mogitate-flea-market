<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Season;

class SeasonSeeder extends Seeder
{
    public function run()
    {
        Season::insert([
            ['name' => '春'],
            ['name' => '夏'],
            ['name' => '秋'],
            ['name' => '冬'],
        ]);
    }
}