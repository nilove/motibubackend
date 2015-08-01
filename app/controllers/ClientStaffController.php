<?php

use Motibu\Models\Client;
use Motibu\Models\ClientStaff;
use Motibu\Transformers\ClientStaffTransformer;

class ClientStaffController extends ApiController {

    /**
     * Create Staff
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
    {
        $success = false;

        if (\Input::has('client_id') && \Input::has('name')) {
            $staffData = \Input::all();
            // TODO: save uploaded image :|

            $staff = ClientStaff::create($staffData);

            // Destination path for uplaoded files which is at /public/uploads
            $destinationPath = public_path().'/uploads/img/';
            // Handle profile Picture
            if(Input::hasFile('profile_pic_filename')){
                $file            = Input::file('profile_pic_filename');
                $profile_pic_filename       = str_random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $uploadSuccess   = $file->move($destinationPath, $profile_pic_filename);

                if($uploadSuccess){
                    $staff->profile_pic_filename = $profile_pic_filename;
                    $staff->save();
                }
            }

            $success = ($staff == true);
        }

        return \Response::json( [
                'success' => $success,
                'data' => ($success)? $staff->getTransformed(new ClientStaffTransformer) : null
            ]
        );
    }

    public function update ($candidate_id)
    {
        try {
            $candidate = Candidate::findOrFail($candidate_id);
            $candidate->fill(Input::all());
            if (\Input::has('skills')) {
                $skills = [];
                foreach (\Input::get('skills') as $skill) {
                    $skills[$skill['skill_id']] = ['description' => isset($skill['description'])? $skill['description']:'',
                    'level' => isset($skill['level'])? $skill['level']:0];
                }
                User::find($candidate->user_id)->skills()->attach($skills);
            }
            $candidate->save();
            return $this->respondWithArray(['success' => true, 'message' => 'Candidate info updated']);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Candidate with ID '.$candidate_id.' not found', 404);
        }

    }

    public function delete ($candidate_id)
    {
        try{
            $candidate = Candidate::findOrFail($candidate_id);
            $candidate->delete();
            return $this->respondWithArray(['success' => true, 'message' => 'Candidate deleted successfully']);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Candidate with ID '.$candidate_id.' not found', 404);
        }
    }

    /**
     * List Client Staff
     * @return \Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        if (\Input::get('client_id')) {
        	if (\Input::has('type'))
	            $staff = Client::find(\Input::get('client_id'))->staff()->whereType(\Input::get('type')->get());
	        else
	            $staff = Client::find(\Input::get('client_id'))->staff;

            return \Response::json( [
            		'data' => $staff
            	]);
        }
    }

}
