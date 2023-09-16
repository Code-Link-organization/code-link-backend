<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Mentor;
use App\Models\User;
use App\Models\Track;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mentor>
 */
class MentorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(), 
            'track_id' => Track::factory(),
            'price' => $this->faker->randomFloat(2, 10, 100), 
            'status' => $this->faker->randomElement(['open', 'closed']),
        ];
    }
}
