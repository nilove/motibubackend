<?php


class OAuthClientController extends Controller {


    public function linkedin(){

        $provider = new Linkedin(Config::get('social.linkedin'));
        if ( !Input::has('code')) {
            // If we don't have an authorization code, get one
            $provider->authorize();
        } else {
            try {
                // Try to get an access token (using the authorization code grant)
                $t = $provider->getAccessToken('authorization_code', array('code' => Input::get('code')));
                try {
                    // We got an access token, let's now get the user's details
                    $userDetails = $provider->getUserDetails($t);
                    $resource = '/v1/people/~:(firstName,lastName,pictureUrl,positions,educations,threeCurrentPositions,threePastPositions,dateOfBirth,location)';
                    $params = array('oauth2_access_token' => $t->accessToken, 'format' => 'json');
                    $url = 'https://api.linkedin.com' . $resource . '?' . http_build_query($params);
                    $context = stream_context_create(array('http' => array('method' => 'GET')));
                    $response = file_get_contents($url, false, $context);
                    return Response::json(['response' => $response]);

                    // Now We have the data, map and save the necessary data into DB.

                } catch (Exception $e) {
                    return 'Unable to get user details';
                }

            } catch (Exception $e) {
                return 'Unable to get access token';
            }
        }
    }
} 