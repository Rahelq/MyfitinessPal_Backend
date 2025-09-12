<?php
// database/seeders/EthiopianFoodCategoriesSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EthiopianFoodCategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['category_name' => 'Injera & Breads', 'description' => 'Traditional Ethiopian flatbreads and pancakes'],
            ['category_name' => 'Wat (Stews)', 'description' => 'Spiced Ethiopian stews with various ingredients'],
            ['category_name' => 'Tibs', 'description' => 'Sautéed or grilled meat dishes'],
            ['category_name' => 'Kitfo', 'description' => 'Traditional minced raw meat dishes'],
            ['category_name' => 'Vegetarian Dishes', 'description' => 'Plant-based Ethiopian dishes'],
            ['category_name' => 'Side Dishes', 'description' => 'Accompaniments to main meals'],
            ['category_name' => 'Traditional Breakfast', 'description' => 'Ethiopian morning meals'],
            ['category_name' => 'Beverages', 'description' => 'Traditional Ethiopian drinks'],
            ['category_name' => 'Legume-Based Dishes', 'description' => 'Dishes primarily made from legumes'],
            ['category_name' => 'Vegetable Dishes', 'description' => 'Vegetable-focused Ethiopian dishes'],
            ['category_name' => 'Snacks & Appetizers', 'description' => 'Ethiopian snacks and appetizers'],
            ['category_name' => 'Holiday & Special Occasion', 'description' => 'Foods for special events and holidays'],
        ];

        foreach ($categories as $category) {
            DB::table('food_categories')->insert([
                'category_name' => $category['category_name'],
                'description' => $category['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}