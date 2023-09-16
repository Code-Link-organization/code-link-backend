<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Track;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Track>
 */
class TrackFactory extends Factory
{
    protected $model = Track::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'name' => $this->faker->unique()->word, 
            'description' => $this->faker->sentence, 
        ];
    }
}
