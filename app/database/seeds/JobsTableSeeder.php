<?php

use Faker\Factory as Faker;
use Motibu\Models\Job;
use Motibu\Models\Agency;

class JobsTableSeeder extends Seeder{

    public function run(){
        $faker = Faker::create();

        $agencies = Agency::lists('id');


        foreach(range(1,50) as $index){
            $job = Job::create([
                'agency_id' => $faker->randomElement($agencies),
                'title' => $faker->sentence(),
                'slug' => $faker->unique()->slug,
                'client_id' => $faker->numberBetween(1,10),
                'agent_id' => $faker->numberBetween(21,40),
                'hr_id' => $faker->numberBetween(1,10),
                'is_published' => true,
                'about' => $faker->paragraph(),
                'salary_range_to'  => $faker->numberBetween(4000,5000),
                'salary_range_from' => $faker->numberBetween(1000,2000),
                'mandate_start' => time(),
                'mandate_end' => time()+86400,
                'age_range_to' => 18,
                'age_range_from' => 35,
                'date_of_entry' => $faker->numberBetween(time()-100000, time()+100000),
                'contract_type_id' => $faker->numberBetween(1, 3)
            ]);

            foreach (range(1, 3) as $index) {
                $job->skills()->attach([$faker->numberBetween(1, 6000) => [
                    'description' => $faker->sentence(),
                    'level' => $faker->numberBetween(1, 100)
                ]]);
            }
        }
    }
}