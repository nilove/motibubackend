<?php

use Faker\Factory as Faker;
use Motibu\Models\Industry;

class IndustriesTableSeeder extends Seeder
{

    public function run()
    {
        $faker = Faker::Create();


        $industriesImports = explode("\n", \File::get('app/dox/industries.txt'));
        $industries = [];
        foreach ($industriesImports as $industry) {
            $industries[] = ['name' => $industry];
        }

        Industry::insert($industries);

    }
}
