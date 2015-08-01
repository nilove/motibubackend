<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Motibu\Models\Client;
use Motibu\Models\ClientStaff;


class ClientsTableSeeder extends Seeder{

    public function run(){
        $faker = Faker::create();

        foreach(range(1,100) as $index) {
            $client = Client::create([
                'name' => $faker->company,
                'about' => $faker->sentence(),
                'location' => $faker->city.' '.$faker->state,
                'industry_id' => $faker->numberBetween(1,10),
                'agency_id' => $faker->numberBetween(1,10)
            ]);

            $staff = ClientStaff::create([
                'name' => $faker->firstName.' '.$faker->lastName,
                'email' => $faker->email,
                'type' => 'hr',
                'client_id' => $client->id,
                'telephone' => $faker->randomNumber(6)
            ]);
        }
    }
}