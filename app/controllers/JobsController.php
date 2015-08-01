<?php

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Motibu\Models\Job;
use Motibu\Models\Agent;
use Motibu\Models\Client;
use Motibu\Models\Agency;
use Motibu\Transformers\CandidateTransformer;
use Motibu\Validators\Jobs as JobsValidator;

use League\Fractal\Manager;

use Motibu\Transformers\JobTransformer;

class JobsController extends ApiController {


    /**
     * Constructor
     * @param Client $Client
     * @param Agency $agency
     * @param JobsValidator $validator
     */
    public function __construct (JobsValidator $validator)
	{
		$this->validator = $validator;

        // call parent construct
        parent::__construct(new Manager);
    }

    /**
     * Show one Agent
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($jobId)
    {
        try {
            $job = Job::with('client', 'skills', 'skills.category', 'hr')->findOrFail($jobId);
            return $this->respondWithArray(['success' => true, 'data' => $job->getTransformed(new JobTransformer, ['skills', 'client', 'hr'])]);
        } catch(ModelNotFoundException $e) {
            return $this->setStatusCode(404)->respondWithError('Job with ID ' . $jobId . ' Not found',404);
        }
    }
    
    /**
     * Create a Job
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
	{
        Log::info(\Input::all());
        $inputdata=\Input::all();
		$success = false;
        $inputdata["mandate_start"]=strtotime($inputdata["mandate_start"]);
        $inputdata["mandate_end"]=strtotime($inputdata["mandate_end"]);
        $inputdata["date_of_entry"]=strtotime($inputdata["date_of_entry"]);
		if ($this->validator->validate(\Input::all())) {
            $job = Job::create($inputdata);
            if (\Input::has('skills')) {
                $skills = [];
                foreach (\Input::get('skills') as $skill) {
                    $skills[$skill['skill_id']] = ['description' => isset($skill['description'])? $skill['description']:'',
                    'level' => isset($skill['level'])? $skill['level']:0];
                }
                $job->skills()->attach($skills);
            }
            if (\Input::get('agent_id')) {
                $agent = Agent::find(\Input::get('agent_id'));
                // $job->agents()->attach($agent->user_id);
                $job->agent_id = $agent->user_id;
            }
        }

		$success = $job == true;

		return \Response::json( [
				'success' => $success
			]
		);
	}

    /**
     * Update a job
     * @param $job_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($job_id){
        $job = Job::findOrFail($job_id)->first();

        if($job){
            $job->fill(Input::all())->save();
        }
        return $this->respondWithArray(['success' => true, 'message' => 'Job updated successfully']);
    }

    /**
     * Delete a Job
     * @param $job_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($job_id){
        $job = Job::find($job_id)->first();
        $job->delete();

        return $this->respondWithArray(['success' => true, 'message' => 'Job deleted successfully']);
    }

    /**
     * Lists Job
     * @return \Illuminate\Http\JsonResponse
     */
    public function index() {
        $jobs = Job::with('skills')->paginate();
//        $jobs = $paginator->getCollection();

        return $this->respondWithCollection($jobs, new JobTransformer, ['skills']);
    }

    

    public function candidates($job_id){
        if ($job_id) {
            $candidates = Job::findOrFail($job_id)->candidates;

            $return_array = $candidates->map(function($candidate){
                //Log::info($candidate->candidateProfile);
               return [
                   'user_id' => $candidate->id,
                   'candidate_id' => $candidate->candidateProfile->id,
                   'name' => $candidate->first_name.' '.$candidate->last_name,
                   'about' => $candidate->candidateProfile->about,
                   "profile_pic"=>url('/uploads/img/'.$candidate->candidateProfile->profile_pic_filename),
                   "location" => $candidate->candidateProfile->residency,
                   "nationality"=>$candidate->candidateProfile->nationality,
                   "years_of_experience"=>$candidate->candidateProfile->years_of_experience
               ] ;
            });

            return $this->respondWithArray($return_array->toArray());
            // will use Transformer later.
//            $paginated = Paginator::make($candidates->toArray(), $candidates->count(), 10);
//            return $this->respondWithCollection($paginated, new CandidateTransformer);
        }

        return $this->respondWithArray([
            'success' => 'false',
            'message' => 'Job ID Not found'
        ]);
    }

    public function addcandidates($job_id){
        $user_id = Input::get('user_id');

        $user = User::find($user_id);

        Job::findOrFail($job_id)->candidates()->attach($user);

        return $this->respondWithArray([
            'success' => true,
            'message' => 'candidate added to job'
        ]);
    }
}
