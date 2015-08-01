<?php

namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Candidate;
use Motibu\Models\User;

/**
 * Class UserTransformer
 * @package Motibu\Transformers
 */
class UserTransformer extends TransformerAbstract  {


    /**
     * List of available Embeds
     * @var array
     */
    protected $availableIncludes = [
        'candidateProfile',
        'skills',
    ];

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(User $user)
    {
        $def = [
            'id' => $user->id,
            'first_name'          => $user->first_name,
            'last_name'          => $user->last_name,
            'username'          => $user->username,
            'email'             => $user->email,
            'gravatar'          => $user->present()->gravatar(50),
            'last_message_read' => $user->meta->last_message_read,
            'last_notification_read' => $user->getMeta()->last_notification_read,
        ];


        // TODO: move to an include
        if ($user->skills) {
            foreach ($user->skills as $skill) {
                $def['skills'][] = [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'description' => $skill->pivot->description,
                    'level' => $skill->pivot->level,
                ];
            }
        }

        return $def;
    }

    public function includeSkills(User $user){
        $skills = $user->skills;

        return $this->collection($skills, new SkillTransformer);
    }

    public function includeCandidateProfile(User $user)
    {
        $candidateProfile = $user->candidateProfile;

        return $this->collection($candidateProfile, new CandidateTransformer);
    }
}