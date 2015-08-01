<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Motibu\Models\Job;
use Motibu\Models\Agency;
use Motibu\Models\User;
use Motibu\Models\Candidate;
use Motibu\Validators\Candidate as CandidateValidator;
use Motibu\Validators\User as UserValidator;

use League\Fractal\Manager as FractalManager;

use Motibu\Transformers\CandidateTransformer;
use Motibu\Transformers\JobTransformer;

/**
 * Handles all api endpoints starting with /candidates/*
 * Class CandidateController
 */

class CandidatesController extends ApiController {

    /**
     * @param CandidateValidator $validator
     */
    public function __construct (CandidateValidator $validator)
    {

        $this->validator = $validator;

        // call parent construct
        parent::__construct(new FractalManager);
    }

    /**
     * Create Candidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
    {
        $success = false;

        if ($this->validator->validate(\Input::all())) {
            $candidateData = \Input::all();
            $candidateData['user_id'] = \Auth::user()->id;
            // TODO: save uploaded image :|
            $candidate = Candidate::create($candidateData);
            $success = ($candidate == true);
        }

        return \Response::json( [
                'success' => $success,
                'data' => $candidate->getTransformed(new CandidateTransformer)
            ]
        );
    }

    public function update ($candidate_id)
    {
        try {

            Log::info(Input::all());
            $candidate = Candidate::findOrFail($candidate_id);
    


            $candidate->fill(Input::all());
            $user = User::find($candidate->user_id);
            if (\Input::has('skills')) {
                $skills = [];
                
                foreach (\Input::get('skills') as $skill) {
                    $skills[$skill['skill_id']] = ['description' => isset($skill['description'])? $skill['description']:'',
                    'level' => isset($skill['level'])? $skill['level']:0];
                }
                $user->skills()->detach();
                $user->skills()->sync($skills);
            }

            // Destination path for uplaoded files which is at /public/uploads
            $destinationPath = public_path().'/uploads/img/';
            // Handle profile Picture
            if(Input::hasFile('profile_pic_filename')){
                $file            = Input::file('profile_pic_filename');
                $profile_pic_filename        = str_random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $uploadSuccess   = $file->move($destinationPath, $profile_pic_filename);

                if($uploadSuccess){
                    $candidate->profile_pic_filename = $profile_pic_filename;
                }
            }

            $candidate->save();
            $user->candidateProfile->profile_pic_filename=url('/uploads/img/'.$user->candidateProfile->profile_pic_filename);
            $response = $user->candidateProfile;
            $response['skills'] = $user->skills;
            return $this->respondWithArray(['success' => true, 'message' => 'Candidate info updated', 'candidate_profile' => $response]);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Candidate with ID '.$candidate_id.' not found', 404);
        }

    }


    public function updatefrommoticard($candidate_id)
    {

        try 
        {
            Log::info(\Input::all());

            $candidate = Candidate::findOrFail($candidate_id);
            
            $user = User::find($candidate->user_id);

            $user->first_name = \Input::get("firstName");
                
            $user->last_name = \Input::get("lastName");

            $user->save();

            if (\Input::has('skills')) {
            
                $skills = [];
                
                foreach (\Input::get('skills') as $skill) {
            
                    $skills[$skill['skill_id']] = ['description' => isset($skill['description'])? $skill['description']:'',
            
                    'level' => isset($skill['level'])? $skill['level']:0];
                
                }
                
                $user->skills()->detach();
                
                $user->skills()->sync($skills);
            }

            $candidate->gender_id = \Input::get('gender');
            
            $candidate->date_of_birth = \Input::get('dateOfBirth');
            
            $candidate->about = \Input::get('about');
            
            $candidate->residency = \Input::get('residence');
            
            $candidate->telephone = \Input::get('telephone'); 
            
            $candidate->years_of_experience = \Input::get('yearsOfExperience');
            
            $candidate->nationality = \Input::get('nationality');
            
            $candidate->inline_skills = \Input::get('inlineSkills');
            
            $candidate->profile_url = \Input::get('profile_url');  

            $candidate->age = \Input::get('age');
            
            $candidate->expected_salary = \Input::get('expected_salary');    
            
            $candidate->location_latitude = \Input::get('location_latitude'); 
            
            $candidate->location_longitude = \Input::get('location_longitude'); 

            $destinationPath = public_path().'/uploads/img/';
            
            $parts = explode("/",\Input::get('profileImage'));

            $file_name = array_pop($parts);

            $myfile = fopen("{$destinationPath}{$file_name}", "w") or die("Unable to open file!");
            
            $imagedata=$this->file_get_contents_curl(\Input::get('profileImage'));
            //Log::info($imagedata);
            fwrite($myfile, $imagedata);
            
            fclose($myfile);

            $candidate->profile_pic_filename = $file_name;
            
            $candidate->save();

            $user->candidateProfile->profile_pic_filename = \Input::get('profileImage');
            
            $response = $user->candidateProfile;
            
            $response['skills'] = $user->skills;

            return $this->respondWithArray(['success' => true, 'message' => 'Candidate info updated', 'candidate_profile' => $response]);

        }
        catch(ModelNotFoundException $e)
        {
            return $this->setStatusCode(404)->respondWithError('Candidate with ID '.$candidate_id.' not found', 404);
        }

    }

    public function delete ($candidate_id)
    {
        try
        {
            $candidate = Candidate::findOrFail($candidate_id);

            $candidate->delete();
            
            return $this->respondWithArray(['success' => true, 'message' => 'Candidate deleted successfully']);
        }
        catch(ModelNotFoundException $e)
        {
            return $this->setStatusCode(404)->respondWithError('Candidate with ID '.$candidate_id.' not found', 404);
        }
    }

    /**
     * Show one Candidate
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($candidateId)
    {
        try 
        {
            $candidate = Candidate::findOrFail($candidateId);
        
            $user = User::find($candidate->user_id);
        
            $user->candidateProfile->profile_pic_filename=url('/uploads/img/'.$user->candidateProfile->profile_pic_filename);
        
            $response = $user->candidateProfile;
        
            $response['skills'] = $user->skills;
        
            $userArray = $user->toArray();
        
            unset($userArray['candidate_profile']);
        
            unset($userArray['skills']);
        
            $response['user'] = $userArray;
        
            return \Response::json($response);
        } 
        catch(ModelNotFoundException $e) 
        {
            return $this->setStatusCode(404)->respondWithError('Client with ID ' . $candidateId . ' Not found',404);
        }
    }

    /**
     * List Jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobs($candidateId)
    {
        $candidate = Candidate::findOrFail($candidateId);
        
        $user = User::find($candidate->user_id);
        
        $jobs = $user->jobsAppliedTo()->with('skills')->paginate();

        return $this->respondWithCollection($jobs, new JobTransformer, ['skills']);
    }
    

    public function searchjob()
    {
        $skillids=array();
     
        $skilllist=json_decode(\Input::get("skills"),true);

        foreach ($skilllist as $d) 
        {
            $skillids[]=(int)($d["value"]);            
        }
     
        $latitude = \Input::get('location_latitude');
     
        $longitude = \Input::get('location_longitude');
     
        $skilbaseids=DB::table('skill_to_job')->whereIn("skill_id",$skillids)
                                              ->lists('job_id');
        $joball = \Motibu\Models\Job::all();
      
        $jobIds=array();

        foreach ($joball as $key => $d) 
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
                //print_r($d);
                $jobIds[]=$d->id;
            }
        } 

       

        if(!empty($jobIds) && !empty($skilbaseids))
        {
            $jobIds=array_intersect($jobIds,$skilbaseids);    
        }   
        elseif(!empty($skilbaseids))
        {
            echo "string";
            $jobIds=$skilbaseids;
        }
        
        $jobs = \Motibu\Models\Job::with("skills")->whereIn('id',$jobIds)->paginate();
        //return $jobs;
        return $this->respondWithCollection($jobs, new JobTransformer, ['skills']);
    }


    /**
     * List Candidates
     * @return \Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        if (\Input::get('job_id')) {
            $candidates = Job::find(\Input::get('job_id'))->candidates;
            return $this->respondWithCollection($candidates, new CandidateTransformer);
        } else {
            $candidates = Candidate::paginate();
            return $this->respondWithCollection($candidates, new CandidateTransformer);
        }

    }


    function file_get_contents_curl($Url)
    {

        //Log::info($Url);
        if (!function_exists('curl_init'))
        {
            die('Sorry cURL is not installed!');
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_REFERER, "http://alquran.org.bd");
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/35.0.1916.153 Safari/537.36");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        curl_close($ch);
        //Log::info($output);
        return $output;
    }
}
