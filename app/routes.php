<?php

// Route::get('/', function()
// {
// 	return View::make('hello');
// });

Route::get('/hello', function(){
   return Response::make('Hello');
});

Route::get('/', 'HomeController@showWelcome');

Route::post('/v1/messages/send', 'Motibu\Controllers\MessagesController@send');

Route::post('/v1/jobs/apply', 'Motibu\Controllers\JobsController@apply');
Route::post('/v1/jobs/delete', 'Motibu\Controllers\JobsController@delete');


/** These routes only needs Client token */
Route::group(array('prefix' => 'v1', 'before' => ['oauth.add_auth_header','oauth']), function(){
    Route::post('register', 'UsersController@register');
    Route::post('register_agency', 'UsersController@register_agency');
    Route::get('register/verify/{confirmation_code}', 'UsersController@confirm');

    // Handle Invites
    Route::get('invite/verify/{code}', 'InviteController@verify');
    Route::post('invite/claim', 'InviteController@claim');
});


/** client token routes ends here */


/**
 * Public API Routes
 */

// TODO: get this out of hear. maybe a search controller
Route::group(array('prefix' => 'v1/public/', 'after' => ['allow_cross_origin']), function()
{
    Route::get('searchjob', 'CandidatesController@searchjob');

    Route::get('skillcategories', function () {
        if (\Input::get('list') == true) {
            $data = Motibu\Models\SkillCategory::lists('name', 'id');
        } else {
            $data = Motibu\Models\SkillCategory::all();
        }

        return \Response::json( [
                'data' => $data
            ]);
    });

    Route::get('skills', function () {
        if (\Input::get('list')) {
            // looking for skills of a category
            if (\Input::get('skill_category_id'))
                $data = Motibu\Models\Skill::whereSkillCategoryId(\Input::get('skill_category_id'))->lists('name', 'id');
            else
                $data = Motibu\Models\Skill::lists('name', 'id');
        } else {
            $data = Motibu\Models\Skill::all();
        }

        return \Response::json( [
                'data' => $data
            ]);
    });

    Route::get('/search', function () {
        // filtration algorithm stuff goes here

        $skillids=array();
        
        $skilllist=json_decode(\Input::get("skills"),true);

        foreach ($skilllist as $d) 
        {
            $skillids[]=(int)($d["value"]);            
        }

        

        // location filter with fix 1200 (1.2 degrees) threshold
        $latitude = \Input::get('location_latitude');
        $longitude = \Input::get('location_longitude');


        $skilbaseids=DB::table('candidate_to_skill')->whereIn("skill_id",$skillids)->lists('user_id');
        
        Log::info($skilbaseids);
        
        $userall = \Motibu\Models\Candidate::all();
        $userIds=array();
        if($latitude != "" && $longitude != "")
        foreach ($userall as $key => $d) 
        {
           // $distance = getDistance($latitude,$longitude,$d->location_latitude,$d->location_longitude);
            

            $earth_radius = 6371;

            $dLat = deg2rad( $d->location_latitude - $latitude );  
            $dLon = deg2rad( $d->location_longitude - $longitude );  

            $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude)) * cos(deg2rad($d->location_latitude)) * sin($dLon/2) * sin($dLon/2);  
            $c = 2 * asin(sqrt($a));  
            $distance = $earth_radius * $c;  

            


            if($distance <= 100)
            {
                $userIds[]=$d->user_id;
            }
        }
        
        

        // subsequent filters will be using the $userIds variable

        if(!empty($userIds) && !empty($skilbaseids))
        {
            $userIds=array_intersect($userIds,$skilbaseids);    
        }                
        elseif(!empty($skilbaseids))
        {
            $userIds=$skilbaseids;      
        }

        Log::info($userIds);
        
        $users = \Motibu\Models\Candidate::with('user')->whereIn('user_id', $userIds)->paginate();

        $fractal = new \League\Fractal\Manager;

        $collection = $users->getCollection();
        $resource = new \League\Fractal\Resource\Collection($users, new \Motibu\Transformers\CandidateTransformer);
        $resource->setPaginator(new \League\Fractal\Pagination\IlluminatePaginatorAdapter($users));
        
        $rootScope = $fractal->createData($resource);
        
        return \Response::json( [
            'professionals' => $rootScope->toArray(),
        ]);
    });

    Route::post('/place_order', function () {
        // TODO: here's where you do the stripe parts

        // simply adding a subscription to an user
        return \Response::json([
            'success' => true
        ]);

    });
});



/**
 * Base URL will be api.motibu.com
 * Api Version Prefix
 */

Route::group(array('prefix' => 'v1', 'before' => ['oauth.add_auth_header', 'oauth', 'oauth.authenticate', 'auth.permissions'], 'after' => ['allow_cross_origin']), function()
{

    Route::get('messages/{user_1_id}/{user_2_id}', function ($user1, $user2) {
        if (Auth::user()->id !== $user1 && Auth::user()->id !== $user2) \App::abort(403, 'Unauthorized action.');
        $messages = \Motibu\Models\Message::
            where('job_id', '=', null)
            ->where(function ($query) use($user1, $user2) {
                $query->where('sender_id', '=', $user1)
                      ->where('recipient_id', '=', $user2);
            })->orWhere(function ($query) use($user1, $user2) {
                $query->where('recipient_id', '=', $user1)
                      ->where('sender_id', '=', $user2);
            })
            ->get();

        return Response::json($messages);
    });

    Route::post('messages/{recipient_id}', function ($recipientId) {
        $payload = \Input::get('payload');
        $success = \Motibu\Models\Message::create(['sender_id'=>Auth::user()->id, 'recipient_id'=>$recipientId, 'payload'=>$payload]);

        return Response::json(['success' => !!$success]);
    });


    /**
     * Jobs Routes
     */
    /*


    Route::post('messages/send', 'MessagesController@send');
    */

    Route::get('jobs', 'JobsController@index');
    Route::post('jobs/create', 'JobsController@create');

    Route::post('jobs/{job_id}/update', 'Motibu\Controllers\JobsController@update');
    Route::post('jobs/{job_id}/delete', 'JobsController@delete');
    Route::get('jobs/{job_id}/show', 'JobsController@show');
    
    Route::get('jobs/{job_id}/candidates','JobsController@candidates');
    Route::post('jobs/{job_id}/candidates','JobsController@addcandidates');

    Route::get('jobs/{job_id}/messages', function ($jobId) {
        // if (Auth::user()->id !== $user1 && Auth::user()->id !== $user2) \App::abort(403, 'Unauthorized action.');
        $user1 = Auth::user()->id;
        $user2 = \Motibu\Models\Job::find($jobId)->agent_id;
        $messages = \Motibu\Models\Message::
            with('sender')
            ->where('job_id', '=', $jobId)
            ->where(function ($query) use($user1, $user2) {
                $query->where('sender_id', '=', $user1)
                      ->where('recipient_id', '=', $user2);
            })->orWhere(function ($query) use($user1, $user2) {
                $query->where('recipient_id', '=', $user1)
                      ->where('sender_id', '=', $user2);
            })
            ->get();

        return Response::json($messages);
    });

    Route::post('jobs/{job_id}/messages', function ($jobId) {
        $payload = \Input::get('payload');
        $recipientId = \Motibu\Models\Job::find($jobId)->agent_id;
        $success = \Motibu\Models\Message::create(['sender_id'=>Auth::user()->id, 'recipient_id'=>$recipientId, 'job_id'=>$jobId, 'payload'=>$payload]);

        if ($success) {
            $wsClient = new Motibu\Services\WebsocketClient;
            $wsClient->connect('127.0.0.1', 8081, '/');
            $payload = json_encode([
                'type' => 'check_messages',
                'userId' => $recipientId
            ]);
            $wsClient->sendData($payload);
        }

        return Response::json(['success' => !!$success]);
    });

    /**
     * Client Routes
     */

    Route::get('clients','ClientsController@index');
    Route::post('clients','ClientsController@create');
    Route::post('clients/{client_id}/update','ClientsController@update');
    Route::post('clients/{client_id}/delete','ClientsController@delete');
    // nested
    Route::get('clients/{client_id}/jobs','ClientsController@jobs');

    /**
     * Agency Routes
     */
    Route::get('agencies', 'AgenciesController@index');
    Route::post('agencies', 'AgenciesController@create');
    Route::get('agencies/{agency_id}/show', 'AgenciesController@show');
    Route::post('agencies/{agency_id}/update', 'AgenciesController@update');
    Route::post('agencies/{agency_id}/delete', 'AgenciesController@delete');
    Route::get('agencies/{agency_id}/clients', 'AgenciesController@clients');
    Route::get('agencies/{agency_id}/agents', 'AgenciesController@agents');
    Route::get('agencies/{agency_id}/jobs', 'AgenciesController@jobs');

    /**
     * Client Routes
     */
    Route::post('clients', 'ClientsController@create');
    Route::get('clients/{client_id}', 'ClientsController@show');
    Route::post('clients/{client_id}/clientstaff', 'ClientsController@clientstaff');

    /**
     * Agent Routes
     */
    Route::post('agents', 'AgentsController@create');
    Route::get('agents/{agent_id}/show', 'AgentsController@show');
    Route::get('agents/{agent_id}/jobs', 'AgentsController@jobs');

    /**
     * Candidate Routes
     */
    Route::post('candidates', 'CandidatesController@create');
    Route::post('candidates/{candidate_id}/update', 'CandidatesController@update');
    Route::get('candidates/{candidate_id}/show', 'CandidatesController@show');
    Route::get('candidates/{candidate_id}/jobs', 'CandidatesController@jobs');

    /**
     * Client Staff Routes
     */
    Route::get('clientstaff', 'ClientStaffController@index');
    Route::post('clientstaff', 'ClientStaffController@create');

    /**
     * Job Routes
     */
    Route::post('jobs', 'JobsController@create');

    // TODO: this shit is just wrong
    // GOTTA REFACTOR THIS TO BE RESTFUL
    // PS: Deadlines can bring out the inner retard
    Route::get('industries', function () {
        return Response::json( [
                'data' => Motibu\Models\Industry::all()
            ]);
    });
    Route::get('skillcategories', function () {
        if (\Input::get('list') == true) {
            $data = Motibu\Models\SkillCategory::lists('name', 'id');
        } else {
            $data = Motibu\Models\SkillCategory::all();
        }

        return \Response::json( [
                'data' => $data
            ]);
    });

    Route::get('skills', function () {
        if (\Input::get('list')) {
            // looking for skills of a category
            if (\Input::get('skill_category_id'))
                $data = Motibu\Models\Skill::whereSkillCategoryId(\Input::get('skill_category_id'))->lists('name', 'id');
            else
                $data = Motibu\Models\Skill::lists('name', 'id');
        } else {
            // looking for a user's skills
            if (\Input::get('user_id'))
                $data = Motibu\Models\User::find(\Input::get('user_id'))->skills;
            // looking for a job's required skills
            else if (\Input::get('job_id'))
                $data = Motibu\Models\Job::find(\Input::get('job_id'))->skills;
            // dump errythang
            else
                $data = Motibu\Models\Skill::all();
        }

        return \Response::json( [
                'data' => $data
            ]);
    });

    /**
     * Users Routes
     */

    /*
     * Login route for User session management over Stateless/Sessionless API
     */
    Route::get('users/login',['before' => 'auth.token', 'uses' => 'UsersController@login']);
    Route::post('users/logout', function () {
        $access_token = Input::get('access_token', str_replace('Bearer ', '', getallheaders()['Authorization']));
        $token = Motibu\Models\OauthAccessToken::findById($access_token);
        $token->expire_time = 0;
        $token->save();
    });

    Route::get('users','UsersController@index');
//    Route::post('users','UsersController@create');
    Route::post('users/{user_id}/update','UsersController@update');
    Route::post('users/{user_id}/update','UsersController@update');
    Route::post('users/{user_id}/delete','UsersController@delete');


    Route::post('me', function () {
        $response['user'] = Auth::user();
        if (Auth::user()->agencies->count()) {
            $response['user']['agencies'] = Auth::user()->agencies;
            $response['user']['is_agency_admin'] = true;
        } else {
            unset($response['user']['agencies']);
        }
        if (Auth::user()->candidateProfile) {
            $response['user']['candidate_profile'] = Auth::user()->candidateProfile;
            $response['user']['candidate_profile']['skills'] = Auth::user()->skills;
            $response['user']['is_professional'] = true;
        } else {
            unset($response['user']['candidate_profile']);
        }
        if (Auth::user()->agentProfile) {
            $response['user']['agent_profile'] = Auth::user()->candidateProfile;
            $response['user']['is_agent'] = true;
        } else {
            unset($response['user']['agent_profile']);
        }
        $response['user']->meta = \Auth::user()->getMeta();
        return Response::json($response['user']);
    });
});

Route::post('v1/oauth/access_token', function()
{
    $response = Authorizer::issueAccessToken();
    $response['user'] = Auth::user();
    if (Auth::user()->agencies->count()) {
        $response['user']['agencies'] = Auth::user()->agencies;
        $response['user']['is_agency_admin'] = true;
    } else {
        unset($response['user']['agencies']);
    }
    if (Auth::user()->candidateProfile) {
        //Auth::user()->candidateProfile->profile_pic_filename=url('/uploads/img/'.Auth::user()->candidateProfile->profile_pic_filename);
        Auth::user()->candidateProfile->profile_pic_filename=url('/uploads/img/'.Auth::user()->candidateProfile->profile_pic_filename);
        
        $response['user']['candidate_profile'] = Auth::user()->candidateProfile;
        $response['user']['candidate_profile']['skills'] = Auth::user()->skills;
        $response['user']['is_professional'] = true;
        $response['user']['moticard']= Auth::user()->MotiCardProfile;

        //Log::info($response);
    } else {
        unset($response['user']['candidate_profile']);
    }
    if (Auth::user()->agentProfile) {
        $response['user']['agent_profile'] = Auth::user()->candidateProfile;
        $response['user']['is_agent'] = true;
    } else {
        unset($response['user']['agent_profile']);
    }
    $response['user']->meta = \Auth::user()->getMeta();
    return Response::json($response);
});

Route::post('v1/oauth/client_cred_grant_token', function(){
   $response = Authorizer::issueAccessToken();
   return Response::json($response);
});


/**
 * Base URL will be api.motibu.com
 * Api Version Prefix
 */

Route::group(array('prefix' => 'moticard', 'before' => ['oauth.add_auth_header', 'oauth.moticards'], 'after' => ['allow_cross_origin']), function()
{
    Route::post('me', function () {
        $response['user'] = Auth::user();
        if (Auth::user()->agencies->count()) {
            $response['user']['agencies'] = Auth::user()->agencies;
            $response['user']['is_agency_admin'] = true;
        } else {
            unset($response['user']['agencies']);
        }
        if (Auth::user()->candidateProfile) {
            $response['user']['candidate_profile'] = Auth::user()->candidateProfile;
            $response['user']['candidate_profile']['skills'] = Auth::user()->skills;
            $response['user']['is_professional'] = true;
        } else {
            unset($response['user']['candidate_profile']);
        }
        if (Auth::user()->agentProfile) {
            $response['user']['agent_profile'] = Auth::user()->candidateProfile;
            $response['user']['is_agent'] = true;
        } else {
            unset($response['user']['agent_profile']);
        }
        $response['user']->meta = \Auth::user()->getMeta();
        return Response::json($response['user']);
    });

    /**
     * Candidate Routes
     */
    Route::post('candidates', 'CandidatesController@create');
    Route::post('candidates/{candidate_id}/update', 'CandidatesController@update');

    Route::post('candidates/{candidate_id}/updatefrommoticard', 'CandidatesController@updatefrommoticard');

    Route::get('candidates/{candidate_id}/show', 'CandidatesController@show');
    Route::get('candidates/{candidate_id}/jobs', 'CandidatesController@jobs');

});



Route::controller('password', 'RemindersController');

Route::get('pulldatafrom/linkedin','OauthClientController@linkedin');
