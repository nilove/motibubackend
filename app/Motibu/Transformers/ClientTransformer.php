<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Client;

class ClientTransformer extends TransformerAbstract {

    protected $availableIncludes = [
      'jobs'
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */

    public function transform(Client $client)
    {
        return [
            'id'  => $client->id,
            'name'  => $client->name,
            'about' => $client->about,
            'contact_name' => $client->contact_name,
            'contact_telephone' => $client->contact_telephone,
            'contact_email' => $client->contact_email,
            'logo_url' => $client->present()->logo_url,
            'num_jobs' => count($client->jobs),
        ];
    }

    public function includeJobs(Client $client){
        $jobs = $client->jobs;

        return $this->collection($jobs, new JobTransformer);
    }

} 