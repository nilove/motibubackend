<?php

class OauthscopeSeeder extends DatabaseSeeder
{
    public function run()
    {
        // scope | name  | description  | created_at  | updated_at

        $scopes = array(

            array(  'scope'        => 'basic',
                'name'         => 'basic',
                'description'  => 'basic scope initial testing',
            ),

            array(  'scope'        => 'generic',
                'name'         => 'generic',
                'description'  => 'generic scope initial testing',
            )

        );

        foreach ($scopes as $scope) {
//            Oauthscope::create($scope);
        }
    }
}