<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Candidate;

class CandidateTransformer extends TransformerAbstract {

    /**
     * Turn this item object into a generic array
     *
     * @param Candidate $candidate
     * @return array
     */
    public function transform(Candidate $candidate)
    {
        $res = [
            'profile_id'=>$candidate->id,
            'about' => $candidate->about,
            'date_of_birth' => $candidate->date_of_birth,
            'residency' => $candidate->residency,
            'telephone' => $candidate->telephone,
            'mobile' => $candidate->mobile,
            'years_of_experience' => $candidate->years_of_experience,
            'nationality' => $candidate->nationality,
            'has_work_permit' => $candidate->has_work_permit,
            'is_married' => $candidate->is_married,
            'num_children' => $candidate->num_children,
            'has_drivers_license' => $candidate->has_drivers_license,
            'is_available' => $candidate->is_available,
            'is_employed' => $candidate->is_employed,
            'location_name' => $candidate->location_name,
            'social_facebook' => $candidate->social_facebook,
            'social_linked_in' => $candidate->social_linked_in,
            'social_twitter' => $candidate->social_twitter,
            'social_google_plus' => $candidate->social_google_plus,
            'social_instagram' => $candidate->social_instagram,
            'social_youtube' => $candidate->social_youtube,
            'profile_pic_url' => $candidate->present()->profile_pic_url,
            'age'             =>$candidate->age,
            'expected_salary' =>$candidate->expected_salary,
            'is_external_vcard'=>$candidate->is_external_vcard,
            'profile_url'=>$candidate->profile_url
        ];

        if ($candidate->user) {
            $res['name']  = $candidate->user->first_name.' '.$candidate->user->last_name;
        }

        return $res;
    }
}
