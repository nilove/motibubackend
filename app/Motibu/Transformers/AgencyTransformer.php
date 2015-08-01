<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Agency;


class AgencyTransformer extends TransformerAbstract{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Agency $agency)
    {
        return [
            'id' => $agency->id,
            'name' => $agency->name,
            'description' => $agency->description,
            'num_employees_to' => $agency->num_employees_to,
            'num_employees_from' => $agency->num_employees_from,
            'legal_entity' => $agency->legal_entity,
            'industry' => $agency->industry,
            'reg_no' => $agency->reg_no,
            'operational_hours_monday_from' => $agency->operational_hours_monday_from,
            'operational_hours_monday_to' => $agency->operational_hours_monday_to,
            'operational_hours_tuesday_from' => $agency->operational_hours_tuesday_from,
            'operational_hours_tuesday_to' => $agency->operational_hours_tuesday_to,
            'operational_hours_wednesday_from' => $agency->operational_hours_wednesday_from,
            'operational_hours_wednesday_to' => $agency->operational_hours_wednesday_to,
            'operational_hours_thursday_from' => $agency->operational_hours_thursday_from,
            'operational_hours_thursday_to' => $agency->operational_hours_thursday_to,
            'operational_hours_friday_from' => $agency->operational_hours_friday_from,
            'operational_hours_friday_to' => $agency->operational_hours_friday_to,
            'operational_hours_saturday_from' => $agency->operational_hours_saturday_from,
            'operational_hours_saturday_to' => $agency->operational_hours_saturday_to,
            'operational_hours_sunday_from' => $agency->operational_hours_sunday_from,
            'operational_hours_sunday_to' => $agency->operational_hours_sunday_to,
            'social_facebook' => $agency->social_facebook,
            'social_linked_in' => $agency->social_linked_in,
            'social_twitter' => $agency->social_twitter,
            'social_google_plus' => $agency->social_google_plus,
            'social_instagram' => $agency->social_instagram,
            'social_youtube' => $agency->social_youtube,
            'is_client' => $agency->is_client,
            'banner_url' => $agency->present()->banner_url,
            'logo_url' => $agency->present()->logo_url,
        ];
    }



} 