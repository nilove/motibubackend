<?php
// File name: app/database/seeds/OauthclientSeeder.php
class OauthclientSeeder extends DatabaseSeeder
{
    public function run()
    {
        if (\App::environment() == 'testing') return;
        // id | secret  | name  | created_at  | updated_at
        $clients = array(

            array(  'id'        => 'IOS_MOB_APP',
                'secret'    => '$1$w24Qs3SOZ',
                'name'      => 'IOS_MOB_APP'
            ),

            array(  'id'        => 'ANDROID_MOB_APP',
                'secret'    => '$Ja4p70Qb8ElhwWs3SOZ',
                'name'      => 'ANDROID_MOB_APP'
            )
        );

        foreach ($clients as $client) {
            Oauthclient::create($client);
        }
    }

}