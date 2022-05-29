<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            'education',
            'science',
            'technology',
            'fashion',
            'sports'
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate([
                'name' => $category
            ], [
                'name' => $category
            ]);
        }
    }
}