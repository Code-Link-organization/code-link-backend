<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'courseUrl' => $this->faker->url,
            'description' => $this->faker->sentence,
            'track_id' => function () {
                return \App\Models\Track::inRandomOrder()->first()->id;
            },
            'user_id' => function () {
                return \App\Models\User::inRandomOrder()->first()->id;
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
