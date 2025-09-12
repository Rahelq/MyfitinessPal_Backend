<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EthiopianFoodsMasterSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            EthiopianFoodCategoriesSeeder::class,
            EthiopianFoodItemsSeeder::class,
        ]);
    }
}