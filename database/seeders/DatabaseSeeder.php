<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MissionStatus;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

		MissionStatus::create([
			'name' => 'Free'
		]);

		MissionStatus::create([
			'name' => 'Contracted'
		]);

		MissionStatus::create([
			'name' => 'Completed'
		]);
    }
}
