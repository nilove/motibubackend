<?php

use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Motibu\Models\Candidate;
use Motibu\Models\User;
use Motibu\Models\Agency;
use Motibu\Models\Agent;
use Motibu\Models\Plan;

class UsersTableSeeder extends Seeder{

    public function run()
    {
        $faker = Faker::create();
        $candidates = Candidate::lists('id');

        User::create([
            'username' => "demoguy",
            'first_name' => "demo",
            'last_name' => "guy",
            'email' => "demo@motibu.com",
            'password' => 'secret',
            'confirmed' => 1,
            'userable_type' => 'Motibu\Models\Candidate',
            'userable_id' => 1
        ]);
        foreach(range(2, 10) as $index)
        {
            User::create([
                'username' => $faker->userName(),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->email(),
                'password' => 'secret',
                'confirmed' => 1,
                'userable_type' => 'Motibu\Models\Candidate',
                'userable_id' => $faker->randomElement($candidates)
            ]);
        }

        $users = User::all();
        foreach (range(1, 10) as $index) {
            $users[$index-1]->agencies()->attach($index);
            $users[$index-1]->roles()->attach(4); // agency admin
        }

        $locations = [
            [
                'location' => "Piarco International Airport (POS), Golden Grove Road, Piarco, Trinidad and Tobago",
                'latitude' => 10.5976964,
                'longitude' => -61.339527
            ],
            [
                'location' => "Pos, Jalan Semeru Selatan, Dampit, Malang 65181, Republic of Indonesia",
                'latitude' => -8.2109083,
                'longitude' => 112.7520661
            ],
            [
                'location' => "Pos, Jalan W. Monginsidi, Kendari, Kota Kendari 93127, Republic of Indonesia",
                'latitude' => -3.970164,
                'longitude' => 122.588356
            ],
            [
                'location' => "Pos, Jalan Raya Sumorame, Candi, Kabupaten Sidoarjo 61271, Republic of Indonesia",
                'latitude' => -7.4936845,
                'longitude' => 112.7057069
            ],
            [
                'location' => "New Delhi, Delhi, India",
                'latitude' => 28.6139391,
                'longitude' => 77.2090212
            ],
            [
                'location' => "Delhi, Haixi, Qinghai, China",
                'latitude' => 37.369436,
                'longitude' => 97.360985
            ],
            [
                'location' => "Delhi, NY 13753, USA",
                'latitude' => 42.27814009999999,
                'longitude' => -74.91599459999999
            ],
        ];
        // create candidates
        foreach (range(1, 10) as $index) {
            $randLoc = $faker->randomElement($locations);
            $user = User::create([
                'username' => $faker->userName(),
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => ($index==1)? "democandidate@motibu.com":$faker->email(),
                'password' => 'secret',
                'confirmed' => 1,
            ]);

            $user->roles()->attach(3); // candidate

            Candidate::create([
                'user_id' => 10+$index,
                'residency' => $faker->city.', '.$faker->state.' '.$faker->country,
                'telephone' => '5553882838',
                'about' => $faker->paragraph(),
                'location_name' => $randLoc['location'],
                'location_latitude' => $randLoc['latitude']*1000, // increase accuracy in int comparison
                'location_longitude' => $randLoc['longitude']*1000,
            ]);
        }
        
        // create agents
        foreach (range(1, 10) as $index) {
            foreach (range(1, 2) as $bleh) {
                $user = User::create([
                    'username' => $faker->userName(),
                    'first_name' => $faker->firstName,
                    'last_name' => $faker->lastName,
                    'email' => $faker->email(),
                    'password' => 'secret',
                    'confirmed' => 1,
                ]);

                $user->roles()->attach(5); // agent

                Agent::create([
                    'user_id' => $user->id,
                    'telephone' => '5553882838',
                    'agency_id' => $index,
                    'name' => $user->first_name.' '.$user->last_name,
                ]);

                // \DB::table('agent_to_job')->insert( [
                //     'user_id' => $user->id,
                //     'job_id' => $faker->numberBetween(1, 50)
                // ]);
            }
        }

        // Create a Super Admin
        $super = User::create([
            'username' => "demosuper",
            'first_name' => "demo",
            'last_name' => "guy",
            'email' => "demosuper@motibu.com",
            'password' => 'secret',
            'confirmed' => 1,
            'userable_type' => 'Motibu\Models\Candidate',
            'userable_id' => 1
        ]);

        $super->roles()->attach(1);

        Plan::create([
            'title' => 'Basic',
            'description' => 'Monthly Basic Plan',
            'duration' => '30',
            'cost_in_cents' => 2000,
            'meta' => '{"can_contact_candidates": "true", "number_of_jobs_per_month": "5"}'
        ]);
    }
}
