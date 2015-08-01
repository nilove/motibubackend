<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Motibu\Models\Invite;
use Motibu\Models\Job;
use Motibu\Models\Agency;
use Motibu\Models\User;
use Motibu\Models\Role;
use Motibu\Models\Agent;
use Motibu\Validators\Agent as AgentValidator;
use Motibu\Validators\User as UserValidator;

use League\Fractal\Manager as FractalManager;

use Motibu\Transformers\AgentTransformer;
use Motibu\Transformers\JobTransformer;

/**
 * Handles all api endpoints starting with /agents/*
 * Class AgentController
 */

class AgentsController extends ApiController {

    /**
     * @param AgentValidator $validator
     * @param UserValidator $userValidator
     */
    public function __construct (AgentValidator $validator, UserValidator $userValidator)
    {

        $this->validator = $validator;
        $this->userValidator = $userValidator;

        // call parent construct
        parent::__construct(new FractalManager);
    }

    /**
     * Show one Agent
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($agentId)
    {
        try {
            $agent = Agent::with('user')->findOrFail($agentId);
            // return $this->respondWithCollection($agent, new AgentTransformer);
            return $this->respondWithArray(['success' => true, 'data' => $agent->getTransformed(new AgentTransformer, ['user'])]);
        } catch(ModelNotFoundException $e) {
            return $this->setStatusCode(404)->respondWithError('Agent with ID ' . $agentId . ' Not found',404);
        }
    }
    
    /**
     * Show jobs of Agent
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobs ($agentId)
    {
        try {
            // $agent = Agent::with('user', 'user.jobs', 'user.jobs.client')->findOrFail($agentId);
            // return $this->respondWithCollection($agent, new AgentTransformer);
            // return $this->respondWithArray(['success' => true, 'data' => $agent->getTransformed(new AgentTransformer, ['jobs', 'jobs.client', 'user'])]);
            $agent = Agent::findOrFail($agentId);
            $jobs = User::findOrFail($agent->user_id)->jobs()->with('client')->paginate();

            return $this->respondWithCollection($jobs, new JobTransformer, ['client']);
        } catch(ModelNotFoundException $e) {
            return $this->setStatusCode(404)->respondWithError('Agent with ID ' . $agentId . ' Not found',404);
        }
    }
    
    /**
     * Create Agent
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
    {
        $success = false;

        if ($this->validator->validate(\Input::all())
            && $this->userValidator->validate(['email'=> \Input::get('email'), 'password' => \Str::random(8)])
        ) {
            $user = User::create(['email'=>\Input::get('email'), 'username' => \Input::get('name'), 'password' => \Str::random(8)]);
            $agentData = \Input::all();
            $agentData['user_id'] = $user->id;
            // TODO: save uploaded image :|
            // Destination path for uplaoded files which is at /public/uploads
            $destinationPath = public_path().'/uploads/img/';
            // Handle profile Picture
            if(Input::hasFile('profile_pic_filename')){
                $file            = Input::file('profile_pic_filename');
                $propic_filename        = str_random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $uploadSuccess   = $file->move($destinationPath, $propic_filename);

                if($uploadSuccess){
                    $agentData['profile_pic_filename'] = $propic_filename;
                }
            }

            $agent = Agent::create($agentData);

            // Send Invitation Email

            $invitation_code = bin2hex(openssl_random_pseudo_bytes(16));

            $invite = Invite::create([
                'code' => $invitation_code,
                'email' => Input::get('email'),
                'user_id' => $user->id,
                'user_type' => 'Agent'
            ]);

            Mail::send('emails.invitation.invite', ['confirmation' => $invitation_code,
                'client_base_url' => 'http://d.motibu-head.com/'], function($message) {
                $message->to(Input::get('email'))
                    ->subject('You have been invited to motibu.com');
            });

            $user->roles()->attach(Role::findByName('Agent')->id);

            $success = ($user && $agent);
        }

        return \Response::json( [
                'success' => $success,
                'data' => $agent->getTransformed(new AgentTransformer)
            ]
        );
    }

    public function update($agent_id){
        try{
            $agent = Agent::findOrFail($agent_id);
            $agent->fill(Input::all());
            $agent->save();
            return $this->respondWithArray(['success' => true, 'message' => 'Agent info updated']);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Agent with ID '.$agent_id.' not found', 404);
        }

    }

    public function delete($agent_id){
        try{
            $agent = Agent::findOrFail($agent_id);
            $agent->delete();
            return $this->respondWithArray(['success' => true, 'message' => 'Agent deleted successfully']);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Agent with ID '.$agent_id.' not found', 404);
        }
    }

    /**
     * List Agents
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $agents = Agent::paginate();

        return $this->respondWithCollection($agents, new AgentTransformer);
    }
}
