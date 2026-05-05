<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Other'],
            ['name' => 'Automobiles & motoecycles'],
            ['name' => 'Bag & shoes'],
            ['name' => 'Computer & offices'],
            ['name' => 'Health & baauty , hair'],
            ['name' => 'Home & garden , furniture'],
            ['name' => 'Home improvement'],
            ['name' => 'Jewlry & watches'],
            ['name' => 'Men\'s clothing'],
            ['name' => 'Phones & accessoires'],
            ['name' => 'Sports & outdoors'],
            ['name' => 'Toys , kids & baby'],
            ['name' => 'Womens\'s clothing'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
