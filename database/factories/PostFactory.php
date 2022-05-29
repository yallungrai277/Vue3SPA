<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        if (User::count()) {
            $categoryIds = Category::pluck('id');
            return [
                'title' => $this->faker->text(20),
                'content' => $this->faker->paragraphs(4, true),
                'category_id' => $categoryIds->random(),
                'user_id' =>  User::all()->random()->id,
            ];
        }
    }
}