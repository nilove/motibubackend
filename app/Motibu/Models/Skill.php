<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Skill extends \Eloquent {

    use SoftDeletingTrait;

    protected $with = ['category'];
	protected $guarded = ['id'];
	protected $fillable = ['name', 'language','esco_uri','skill_category_id'];

    public $timestamps = false;

	public function category ()
	{
		return $this->belongsTo('Motibu\Models\SkillCategory', 'skill_category_id');
	}

    public function jobs () {
        return $this->belongsToMany('Motibu\Models\Job', 'skill_to_job');
    }
}
