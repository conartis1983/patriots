<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EventType;

class EventTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        EventType::insert([
            ['name' => 'EM', 'event_category_id' => 1],
            ['name' => 'EM-Quali', 'event_category_id' => 1],
            ['name' => 'WM', 'event_category_id' => 1],
            ['name' => 'WM-Quali', 'event_category_id' => 1],
            ['name' => 'Nations League', 'event_category_id' => 1],
            ['name' => 'Freundschaftsspiel', 'event_category_id' => 1],
            ['name' => 'Fanclubtreffen', 'event_category_id' => 2],
            ['name' => 'Weihnachtsfeier', 'event_category_id' => 2],
            ['name' => 'Turnier', 'event_category_id' => 2],
        ]);
    }
}
