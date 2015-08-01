<?php

use Faker\Factory as Faker;
use Motibu\Models\Candidate;
use Motibu\Models\User;

class CandidateTableSeeder extends Seeder{

    public function run(){

        // users 11 to 20 are candidates

        $faker = Faker::create();

        $users = User::all();

        foreach (range(11, 20) as $userId) {
            foreach (range(1,5) as $index) { // number of skills/jobs
                \DB::table('candidate_to_job')->insert( [
                    'user_id' => $userId,
                    'job_id' => $faker->numberBetween(1, 50)
                ]);

                $users[$userId-1]->skills()->attach([$faker->numberBetween(1, 6000) => [
                    'description' => $faker->paragraph(),
                    'level' => $faker->numberBetween(1, 100)
                ]]);
            }
        }
    }
}