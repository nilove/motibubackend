<?php

namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Job;
use Motibu\Models\Client;
use Motibu\Models\Skill;

/**
 * Class JobTransformer
 * @package Motibu\Transformers
 */
class JobTransformer extends TransformerAbstract  {


    /**
     * List of available Embeds
     * @var array
     */
    protected $availableIncludes = [
        'client',
        'skills',
        'hr',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Job $job)
    {
        return [
            'id'                                => $job->id,
            'title'                             => $job->title,
            'description'                       => $job->description,
            'salary_range'                      => $job->salary_from.'-'.$job->salary_to,
            'mandate_start'                     => $job->mandate_start,
            'mandate_end'                       => $job->mandate_end,
            'mandate_is_private'                => $job->mandate_is_private,
            'is_published'                      => $job->is_published,
            'is_published_is_private'           => $job->is_published_is_private,
            'title'                             => $job->title,
            'title_is_private'                  => $job->title_is_private,
            'sector_id'                         => $job->sector_id,
            'sector_id_is_private'              => $job->sector_id_is_private,
            'age_range_from'                    => $job->age_range_from,
            'age_range_to'                      => $job->age_range_to,
            'age_range_is_private'              => $job->age_range_is_private,
            'gender_id'                         => $job->gender_id,
            'gender_id_is_private'              => $job->gender_id_is_private,
            'nationality_id'                    => $job->nationality_id,
            'nationality_id_is_private'         => $job->nationality_id_is_private,
            'work_permit_id'                    => $job->work_permit_id,
            'work_permit_id_is_private'         => $job->work_permit_id_is_private,
            'years_of_experience'               => $job->years_of_experience,
            'years_of_experience_is_private'    => $job->years_of_experience_is_private,
            'min_degree_id'                     => $job->min_degree_id,
            'min_degree_id_is_private'          => $job->min_degree_id_is_private,
            'residence_id'                      => $job->residence_id,
            'residence_id_is_private'           => $job->residence_id_is_private,
            'date_of_entry'                     => $job->date_of_entry,
            'date_of_entry_is_private'          => $job->date_of_entry_is_private,
            'working_hours_from'                => $job->working_hours_from,
            'working_hours_to'                  => $job->working_hours_to,
            'working_hours_is_private'          => $job->working_hours_is_private,
            'salary_range_from'                 => $job->salary_range_from,
            'salary_range_to'                   => $job->salary_range_to,
            'salary_range_is_private'           => $job->salary_range_is_private,
            'about'                             => $job->about,
            'about_is_private'                  => $job->about_is_private,
            'slug'                              => $job->slug,
            'inline_skills'                     => $job->inline_skills,
            'location'                          => $job->location_name
        ];
    }

    public function includeClient(Job $job){
        $client = $job->client;

        return $this->item($client, new ClientTransformer);
    }

    public function includeSkills(Job $job){
        $skills = $job->skills;

        return $this->collection($skills, new SkillTransformer);
    }

    public function includeHr(Job $job){
        $hr = $job->hr;
        
        return $this->item($hr, new ClientStaffTransformer);
    }
}