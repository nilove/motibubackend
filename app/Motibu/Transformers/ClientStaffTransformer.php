<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\ClientStaff;

class ClientStaffTransformer extends TransformerAbstract{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(ClientStaff $staff)
    {
        return [
            'id' => $staff->id,
            'name'          => $staff->name,
            'telephone'    => $staff->telephone,
            'email'        =>$staff->email,
            'profile_pic_url' => $staff->present()->profile_pic_url,
            'type' => $staff->type
        ];
    }
} 