<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            SettingSeeder::class,
            UserSeeder::class,
            TourSeeder::class,
            ExchangeRateSeeder::class,
            ResourceSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
