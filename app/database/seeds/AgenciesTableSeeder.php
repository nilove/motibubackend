<?php

use Faker\Factory as Faker;
use Motibu\Models\Agency;
use Motibu\Models\Agent;
use Motibu\Models\User;

class AgenciesTableSeeder extends Seeder{

    public function run() {
        $faker = Faker::create();

        foreach(range(1, 10) as $index){
            Agency::create([
                'description' => $faker->sentence(),
                'name' => $faker->company,
                'industry_id' => $faker->numberBetween(1, 5000),
            ]);
        }
    }
}