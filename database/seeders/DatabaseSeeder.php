<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Track;
use App\Models\Mentor;
use App\Models\Course;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

    public function run()
  {
    \App\Models\User::factory()->count(10)->create();
    \App\Models\Mentor::factory()->count(10)->create();
    \App\Models\Mentor::factory()->count(10)->create();
    \App\Models\Course::factory()->count(10)->create();

  }

}
