<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Agent;
use Motibu\Models\User;

class AgentTransformer extends TransformerAbstract {

    protected $availableIncludes = [
      'jobs',
      'user'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */

    public function transform(Agent $agent)
    {
      $de=json_decode($agent->user,true);
      \Log::info($de);
        //print_r($agent);
        return [
            'id' => $agent->id,
            'name'  => $agent->name,
            'email' => $de["email"],
            'telephone' => $agent->telephone,
            'profile_pic_filename' => $agent->present()->profile_pic_url,
            'agency_id' => $agent->agency_id,
        ];
    }

    public function includeJobs(Agent $agent){

        $jobs = $agent->jobs;

        return $this->collection($jobs, new JobTransformer);
    }

    public function includeUser(Agent $agent){
        $user = $agent->user;

        return $this->item($user, new UserTransformer);
    }

} 