<?php

use Laracasts\Validation\FormValidationException;
use Motibu\Models\User;
use Motibu\Models\Candidate;
use Motibu\Models\Role;
use Motibu\Models\Agency;
use Motibu\Models\Moticardtoken;
use Motibu\Transformers\UserTransformer;
use Motibu\Validators\User as UserValidator;
use Motibu\Validators\Candidate as CandidateValidator;
use Motibu\Validators\Agency as AgencyValidator;

use League\Fractal\Manager as FractalManager;

class InvalidConfirmationCodeException extends Exception {}


class UsersController extends ApiController {

    /**
     * @var Motibu\Validators\User
     */
    private $userValidator;

    public function __construct(UserValidator $userValidator, CandidateValidator $candidateValidator, AgencyValidator $agencyValidator){


        parent::__construct(new League\Fractal\Manager);
        $this->userValidator = $userValidator;
        $this->candidateValidator = $candidateValidator;
        $this->agencyValidator = $agencyValidator;
    }

    public function index(){
        $users = User::paginate();

        return $this->respondWithCollection($users, new UserTransformer);
    }

    public function login(){
        $user = Auth::user();
        $token = $user->tokens()->where('client', BrowserDetect::toString())->first();

        return $this->respondWithArray(['user' => $user->toArray(), 'token' => $token]);
    }

    public function confirm($confirmation_code)
    {
        $confirmation_code = base64_decode($confirmation_code);
        Log::info($confirmation_code);
        if( ! $confirmation_code)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();
        Log::info(print_r($user->toArray(), true));

        if ( ! $user)
        {
            throw new InvalidConfirmationCodeException;
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();



        return Response::json([
            'success' => true,
            'message' => 'Email confirmed, please login now'
        ]);
    }

    public function register() {
        $success = false;
        $user = Input::only('email','password','confirm_password', 'first_name', 'last_name');
        $user['username'] = $user['email'];
         Log::info(Input::all());    

        try{
            if ($this->userValidator->validate($user)) {
                $newUser = User::create($user);
                $success = $newUser == true;
                // $success = $success &&
                $newUser->roles()->attach(Role::findByName('Candidate')->id);
                if ($this->candidateValidator->validate(['user_id' => $newUser->id])) {
                    $is_external_vcard=0;

                     
                    if(Input::get("is_external_vcard") == 'true')
                    {
                        $is_external_vcard=1;
                    }
                    $success = $success && (Candidate::create(['user_id' => $newUser->id,"is_external_vcard"=>$is_external_vcard]) == true);
                    //Moticardtoken::create(['user_id' => $newUser->id,"token"=>md5("{$newUser->id}".time())]); 
                    $mtoken=new Moticardtoken();
                    $mtoken->user_id=$newUser->id;
                    $mtoken->token=md5("{$newUser->id}".time());
                   
                    $mtoken->save();
               }

            }

        }catch (FormValidationException $e){
            return \Response::json( [
                    'success' => false,
                    'errors' => $e->getErrors()
                , 400]
            );
        }


        Log::info(print_r($user, true));

        if($success){
            $newUser->confirmation_code = Hash::make($newUser->id.str_random(30));
            $newUser->save();

            Mail::send('emails.registration.confirmation', ['confirmation' => base64_encode($newUser->confirmation_code),
                       'client_base_url' => Input::get('client_base_url')], function($message) {
                $message->to(Input::get('email'))
                    ->subject('Verify your email address');
            });
        }

        return \Response::json( [
                'success' => $success
            ]
        );
    }

    public function register_agency() {
        $success = false;
        $user = Input::only('email','password','confirm_password', 'first_name', 'last_name');
        $user['username'] = $user['email'];
//        $user['confirmation_code'] = $confirmation_code =  str_random(30);
        Log::info(print_r($user, true));
        try {
            if ($this->userValidator->validate($user)) {
                $newUser = User::create($user);
                $success = $newUser == true;
                $newUser->roles()->attach(Role::findByName('Agency Admin')->id);

                $agency['name'] = Input::get('agency_name');
                $agency['description'] = 'new agency';
                // try {
                    if ($this->agencyValidator->validate($agency)) {
                        Log::info($agency);
                        $newAgency = Agency::create($agency);
                        $newAgency->admins()->attach($newUser->id);
                    }
                // } catch (FormValidationException $e) {
                    // Log::info(print_r($e->getErrors()));
                    // return \Response::json(['success' => false, 'error' => $e->getErrors()]);
                // }
            }

        } catch (FormValidationException $e) {
            return \Response::json( [
                    'success' => false,
                    'errors' => $e->getErrors()
                , 400]
            );
        }

        Log::info(print_r($user, true));

        if($success) {
            $newUser->confirmation_code = Hash::make($newUser->id.str_random(30));
            $newUser->save();

            Mail::send('emails.registration.confirmation', ['confirmation' => base64_encode($newUser->confirmation_code).'?next_step=3&user_id='.$newUser->id,
                       'client_base_url' => Input::get('client_base_url')], function($message) {
                $message->to(Input::get('email'))
                    ->subject('Verify your email address');
            });
        }

        return \Response::json( [
                'success' => $success
            ]
        );
    }
}
