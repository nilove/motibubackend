<?php

use Illuminate\Database\Seeder;
use Motibu\Models\Role;


class RolesTableSeeder extends Seeder {

    public function run()
    {
        Role::create([
            'name' => 'Super Admin'
        ]);

        Role::create([
            'name' => 'SaaS Client Admin'
        ]);

        Role::create([
            'name' => 'Candidate'
        ]);

        Role::create([
            'name' => 'Agency Admin'
        ]);

        Role::create([
            'name' => 'Agent'
        ]);
    }
}