<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Motibu\Models\Client;
use Motibu\Models\Agent;
use Motibu\Models\Agency;
use Motibu\Transformers\AgencyTransformer;
use Motibu\Transformers\ClientTransformer;
use Motibu\Transformers\AgentTransformer;
use Motibu\Transformers\JobTransformer;
use Motibu\Validators\Agency as AgencyValidator;

use League\Fractal\Manager as FractalManager;


/**
 * Handles all api endpoints starting with /agencies/*
 * Class AgenciesController
 */
class AgenciesController extends ApiController {

    /**
     * @param ClientValidator $validator
     */
    public function __construct ( AgencyValidator $validator)
    {

        $this->validator = $validator;

        // call parent construct
        parent::__construct(new FractalManager);
    }

    /**
     * Show one Agent
     * @return \Illuminate\Http\JsonResponse
     */
    public function show ($agencyId)
    {
        try {
            $agency = Agency::findOrFail($agencyId);
            return $this->respondWithArray(['success' => true, 'data' => $agency->getTransformed(new AgencyTransformer)]);
        } catch(ModelNotFoundException $e) {
            return $this->setStatusCode(404)->respondWithError('Agency with ID ' . $agencyId . ' Not found',404);
        }
    }

    /**
     * Create Client
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
    {
        Log::info(Input::all());
        $success = false;

        if ($this->validator->validate(\Input::all())) {
            $success = Agency::create(\Input::all()) == true;
        }

        return \Response::json( [
                'success' => $success
            ]
        );
    }

    public function update($agency_id){

        Log::info(Input::all());
        try{
            $agency = Agency::findOrFail($agency_id);

            // Destination path for uplaoded files which is at /public/uploads
            $destinationPath = public_path().'/uploads/img/';
            // Handle company Logo and banner files
            if(Input::hasFile('logo_filename')){
                $file            = Input::file('logo_filename');
                $logo_filename        = str_random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $uploadSuccess   = $file->move($destinationPath, $logo_filename);


                if($uploadSuccess){
                    $agency->logo_filename = $logo_filename;
                }
            }

            if(Input::hasFile('banner_filename')){
                $file            = Input::file('banner_filename');
                $banner_filename   = str_random(6) . '_' . $file->getClientOriginalName();
                $uploadSuccess   = $file->move($destinationPath, $banner_filename);

                if($uploadSuccess){
                    $agency->banner_filename = $banner_filename;
                }
            }


            $agency->fill(Input::all());
            $agency->save();
            return $this->respondWithArray(['success' => true,
                'message' => 'Agency info updated',
                'agency' => $agency->getTransformed(new AgencyTransformer)
            ]);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Agency with ID ' . $agency_id . ' Not found',404);
        }

    }

    public function delete($agency_id){
        try{
            $agency = Agency::findOrFail($agency_id)->first();
            $agency->delete();
            return $this->respondWithArray(['success' => true, 'message' => 'Agency deleted successfully']);

        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Agency with ID ' . $agency_id . ' Not found',404);
        }
    }

    /**
     * List agencies
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $agencies = Agency::paginate();

        return $this->respondWithCollection($agencies, new AgencyTransformer);
    }

    /**
     * List clients
     * @return \Illuminate\Http\JsonResponse
     */
    public function clients ($agency_id)
    {
        $agency = Agency::findOrFail($agency_id);
        $clients = $agency->clients()->paginate();

        return $this->respondWithCollection($clients, new ClientTransformer);
    }

    /**
     * List Jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobs ($agency_id)
    {
        $agency = Agency::findOrFail($agency_id);
        $jobs = $agency->jobs()->paginate();

        return $this->respondWithCollection($jobs, new JobTransformer);
    }

    /**
     * List agents
     * @return \Illuminate\Http\JsonResponse
     */
    public function agents ($agency_id)
    {

        $agency = Agency::findOrFail($agency_id);
        //print_r($agency);

        $agents = $agency->agents()->paginate();
        //Log::info($agents);
        return $this->respondWithCollection($agents, new AgentTransformer);
    }
}
