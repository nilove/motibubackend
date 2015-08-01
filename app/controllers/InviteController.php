<?php

use Motibu\Models\Invite;
use Motibu\Models\User;

class InviteController extends ApiController{

    public function verify($code){
        $invite = Invite::where('code', '=', trim($code))
                        ->where('claimed_at', '=', null)
                        ->first();

        Log::info('Hello from Invite Controlelr');
        Log::info($invite);

        if($invite){
            return $this->respondWithArray([
               'success' => true,
               'user_type' => $invite->user_type,
               'message' => 'Code Exists, proceed to password change'
            ]);
        }else{
            return $this->setStatusCode(404)
                 ->respondWithArray([
                        'success' => false,
                        'message' => 'Code is not valid'
                  ]);
        }
    }

    public function claim(){

        Log::info('Inside Claim');
        Log::info(Input::all());
        $code      = trim(Input::get('code'));
        $user_type = trim(Input::get('user_type'));

        $password  = trim(Input::get('password'));
        $confirm_password = trim(Input::get('confirm_password'));

        $invite = Invite::where('code', '=', trim($code))
                  ->where('user_type', '=', $user_type)
                  ->where('claimed_at', '=', null)
                  ->first();

        if($invite && $password == $confirm_password){
            $user = User::find($invite->user_id);
            $user->password = $password;
            $user->confirmed = 1;
            $user->save();

            $this->respondWithArray([
               'success' => true,
                'message' => 'Password Updated successfully. You can now login.'
            ]);
        }else{
            $this->respondWithArray([
                'success' => false,
                'message' => 'Please make sure you clicked on the correct link and you put two identical password.'
            ]);
        }

    }
}