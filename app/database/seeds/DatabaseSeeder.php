<?php

class DatabaseSeeder extends Seeder {

    /**
     * @var array
     */
    protected $tables = [
        'users',
        'roles',
        'jobs',
        'industries',
        'clients',
        'agencies',
        'oauth_clients',
        'oauth_scopes'
    ];

    /**
     * @var array
     */
    protected $seeders = [
        'UsersTableSeeder',
        'CandidateTableSeeder',
        'RolesTableSeeder',
        'ClientsTableSeeder',
        'AgenciesTableSeeder',
        'IndustriesTableSeeder',
        'JobsTableSeeder',
        'OauthclientSeeder',
        'SkillsTableSeeder'
        // 'OauthscopeSeeder'
    ];




    /**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
        Eloquent::unguard();

        $this->cleanDatabase();

        foreach($this->seeders as $seedClass)
        {
            if (\App::environment() == 'testing') {
                if ($seedClass == 'OauthclientSeeder' || $seedClass == 'OauthscopeSeeder') continue;
            }
            $this->call($seedClass);
        }
	}

    /**
     * Clean out the database
     */
    public function cleanDatabase()
    {
        if (\App::environment() !== 'testing')
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
        foreach($this->tables as $table)
        {
            if (\App::environment() == 'testing') {
                if ($table == 'oauth_clients' || $table == 'oauth_scopes') continue;
            }
            DB::table($table)->truncate();
        }
        if (\App::environment() !== 'testing')
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

}
