<?php namespace Motibu\Transformers;

use League\Fractal\TransformerAbstract;
use Motibu\Models\Skill;


class SkillTransformer extends TransformerAbstract{

    /**
     * Turn this item object into a generic array
     *
     * @return array
     */
    public function transform(Skill $skill)
    {
        $def = [
            'id'          => $skill->id,
            'name'    => $skill->name,
        ];

        if ($skill->category) {
            $def['skill_category_id'] = $skill->skill_category_id;
            $def['category_name'] = $skill->category->name;
        }

        if ($skill->pivot && ($skill->pivot->job_id || $skill->pivot->user_id)) {
            $def['description'] = $skill->pivot->description;
            $def['level'] = $skill->pivot->level;
        }

        return $def;
    }



} 