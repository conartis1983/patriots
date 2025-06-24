<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventCategory;

class EventCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EventCategory::insert([
            ['id' => 1, 'name' => 'Ländermatch'],
            ['id' => 2, 'name' => 'Fanclub-Event'],
        ]);
    }
}
