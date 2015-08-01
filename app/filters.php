<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
    // Sent by the browser since request come in as cross-site AJAX
    // The cross-site headers are sent via .htaccess
    if ($request->getMethod() == "OPTIONS") {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization'); // allow certain headers
    }
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/

Route::filter('auth', function()
{
	if (Auth::guest())
	{
		if (Request::ajax())
		{
			return Response::make('Unauthorized', 401);
		}
		else
		{
			return Redirect::guest('login');
		}
	}
});



Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});

/*
|--------------------------------------------------------------------------
| API User Token Authentication
|--------------------------------------------------------------------------
| API tokens will be used to maintain user session
|
*/


Route::filter('auth.token', function($route, $request)
{
    $authenticated = false;

    if($email = $request->headers->get('MOTIBU_AUTH_USER') && $password = $request->headers->get('MOTIBU_AUTH_PW'))
    {
        $credentials = array('email' => $request->headers->get('MOTIBU_AUTH_USER'), 'password' => $request->headers->get('MOTIBU_AUTH_PW'));


       // $auth = App::make('auth');

        if(Auth::once($credentials))
        {
            $authenticated = true;

            if(!Auth::user()->tokens()->where('client',BrowserDetect::toString())->first())
            {
                $token = [];

                $token['api_token'] = hash('sha256',Str::random(10),false);
                $token['client'] = BrowserDetect::toString();
                $token['expires_on'] = Carbon\Carbon::now()->addMonth()->toDateTimeString();

                Auth::user()->tokens()->save(new Token($token));
            }

        }
    }

    if($payload = $request->header('X-Auth-Token'))
    {

        $token = Token::valid()->where('api_token',$payload)
            ->where('client',BrowserDetect::toString())
            ->first();


        if($token)
        {
            Auth::login($token->user);
            $authenticated = true;
        }

    }

    if($authenticated && !Auth::check())
    {
        Auth::login(Auth::user());
    }

    if(!$authenticated)
    {
        $response = Response::json([
                'error' => true,
                'message' => 'End User Not authenticated',
                'code' => 403,
                'credentials'=> array('email' => $request->headers->get('MOTIBU_AUTH_USER'), 'password' => $request->headers->get('MOTIBU_AUTH_PW'))
            ],
            401
        );

        $response->header('Content-Type', 'application/json');

        return $response;
    }


});

\Route::filter('oauth.authenticate', function () {

    $authenticated = false;

    $access_token = Input::get('access_token', str_replace('Bearer ', '', getallheaders()['Authorization']));

    $token = Motibu\Models\OauthAccessToken::findById($access_token);
    if ($token) {
        $user = $token->oauthSession->user;
        \Auth::login($user);
        $authenticated = true;
    }

    // possibly reduntdant at this point since 'oauth' filter runs before this
    if(!$authenticated)
    {
        $response = Response::json([
                'error' => true,
                'message' => 'End User Not authenticated',
                'code' => 401,
                'credentials'=> array('email' => $request->headers->get('MOTIBU_AUTH_USER'), 'password' => $request->headers->get('MOTIBU_AUTH_PW'))
            ],
            401
        );

        $response->header('Content-Type', 'application/json');

        return $response;
    }
});

// ACL and Permissions

\Route::filter('auth.permissions', function ($route) {
    if (!\Auth::check()) return;

    ACL::setUser(\Auth::user());

    // don't handle callbacks
    if (\Route::currentRouteAction() == null) {
        return;
    }

    $closure = explode('@', \Route::currentRouteAction());
    $controllerClass = $closure[0];
    $controllerMethod = $closure[1];
    
    $permissionsDefinitionClass = 'Motibu\Permitters\Definitions\\'.$controllerClass.'Permissions';

    if (class_exists($permissionsDefinitionClass)) {
        $permissionsMap = (new $permissionsDefinitionClass)->getPermissions();
        // no permissions needed for action
        if (!isset($permissionsMap[$controllerMethod])) return;
        $permissions = $permissionsMap[$controllerMethod];
        $permits = true;
        foreach ($permissions as $permission) {
            $permits = $permits && ACL::permits($permission, $route->parameters());
        }

        if (!$permits) {
            \App::abort(403, 'Unauthorized action.');
        }
    }
});

// Allow cross origin requests

\Route::filter('allow_cross_origin', function($route, $request, $response) {
    $response->header('Access-Control-Allow-Origin', '*');
});

\Route::filter('oauth.add_auth_header', function ($route, $request) {
    $headers = getallheaders();
    if (isset($headers['Authorization']))
        $request->headers->set('Authorization', $headers['Authorization']);
});


\Route::filter('oauth.moticards', function () {

    $authenticated = false;

    $access_token = Input::get('access_token', str_replace('Bearer ', '', getallheaders()['Authorization']));

    $token = Motibu\Models\Moticardtoken::where(["token"=>$access_token])->first();
    if ($token) {

        //\Log::info($token->user);
        $user = $token->user;
        \Auth::login($user);
        $authenticated = true;
    }

    // possibly reduntdant at this point since 'oauth' filter runs before this
    if(!$authenticated)
    {
        $response = Response::json([
                'error' => true,
                'message' => 'End User Not authenticated',
                'code' => 401,
                'credentials'=> array('email' => $request->headers->get('MOTIBU_AUTH_USER'), 'password' => $request->headers->get('MOTIBU_AUTH_PW'))
            ],
            401
        );

        $response->header('Content-Type', 'application/json');

        return $response;
    }
});
