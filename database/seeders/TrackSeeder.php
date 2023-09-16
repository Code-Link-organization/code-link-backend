<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Factories\TrackFactory;


class TrackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

     
     public function run()
     {
         // Create 10 fake tracks
         TrackFactory::new()->count(10)->create();
     }
}
