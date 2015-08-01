<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Motibu\Models\Job;
use Motibu\Models\Client;
use Motibu\Models\Agency;
use Motibu\Transformers\ClientStaffTransformer;
use Motibu\Transformers\JobTransformer;
use Motibu\Validators\Client as ClientValidator;

use League\Fractal\Manager as FractalManager;

use Motibu\Transformers\ClientTransformer;

/**
 * Handles all api endpoints starting with /clients/*
 * Class ClientsController
 */
class ClientsController extends ApiController {

    /**
     * @param ClientValidator $validator
     */
    public function __construct ( ClientValidator $validator)
    {

        $this->validator = $validator;

        // call parent construct
        parent::__construct(new FractalManager);
    }


    /**
     * Create Client
     * @return \Illuminate\Http\JsonResponse
     */
    public function create ()
    {
        $success = false;

        if ($this->validator->validate(\Input::all())) {
            Log::info(Input::all());
            $client = Client::create(\Input::all());
            $success = $client == true;

            // Destination path for uplaoded files which is at /public/uploads
            $destinationPath = public_path().'/uploads/img/';
            // Handle profile Picture
            if(Input::hasFile('logo_filename')){
                $file            = Input::file('logo_filename');
                $logo_filename        = str_random(6) . '_' . str_replace(' ', '_', $file->getClientOriginalName());
                $uploadSuccess   = $file->move($destinationPath, $logo_filename);

                if($uploadSuccess){
                    $client->logo_filename = $logo_filename;
                    $client->save();
                }
            }
        }

        return \Response::json( [
                'success' => $success
            ]
        );
    }

    /**
     * Show Client
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($client_id)
    {
        try {
            $client = Client::findOrFail($client_id);
            return $this->respondWithArray(['success' => true, 'data' => $client->getTransformed(new ClientTransformer)]);
        } catch(ModelNotFoundException $e) {
            return $this->setStatusCode(404)->respondWithError('Client with ID ' . $client_id . ' Not found',404);
        }

    }

    public function update($client_id){
        try{
            $client = Client::findOrFail($client_id)->first();
            $client->fill(Input::all());
            $client->save();
            return $this->respondWithArray(['success' => true, 'message' => 'Client info updated']);
        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Client with ID ' . $client_id . ' Not found',404);
        }

    }

    public function delete($client_id){
        try{
            $client = Client::findOrFail($client_id)->first();
            $client->delete();
            return $this->respondWithArray(['success' => true, 'message' => 'Client deleted successfully']);

        }catch(ModelNotFoundException $e){
            return $this->setStatusCode(404)->respondWithError('Client with ID ' . $client_id . ' Not found',404);
        }
    }

    /**
     * List Clients
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(){
        $clients = Client::paginate();
//        $jobs = $paginator->getCollection();

        return $this->respondWithCollection($clients, new ClientTransformer);
    }

    /**
     * List Client Staff
     * @return \Illuminate\Http\JsonResponse
     */
    public function clientstaff ($client_id)
    {
        if (\Input::has('type'))
            $staffs = Client::find($client_id)->staff()->whereType(\Input::get('type'))->get();
        else
            $staffs = Client::find($client_id)->staff;

        $outputArray = [];

        foreach($staffs as $staff){
            $outputArray[] = $staff->getTransformed(new ClientStaffTransformer)['data'];
//            Log::info($staff->getTransformed(new ClientStaffTransformer));
        }
        return \Response::json( [
                'data' => $outputArray
            ]);
    }

    /**
     * List Client Jobs
     * @return \Illuminate\Http\JsonResponse
     */
    public function jobs ($client_id)
    {
        $jobs = Client::find($client_id)->jobs()->with('skills')->paginate();

        return $this->respondWithCollection($jobs, new JobTransformer, ['skills']);
    }
}
