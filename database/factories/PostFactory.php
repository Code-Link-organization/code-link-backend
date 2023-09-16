<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
// use Database\Factories\User;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [

            'content' => $this->faker->paragraph,
            'image_path' => $this->faker->imageUrl,
            'comments_count' => $this->faker->numberBetween(0, 100),
            'likes_count' => $this->faker->numberBetween(0, 100),
            'shares_count' => $this->faker->numberBetween(0, 100),
            'user_id' => User::factory(),
            'shareduser_id' => User::factory(),


        ];
    }
}
