<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class SkillCategory extends \Eloquent {

    use SoftDeletingTrait;

	protected $guarded = ['id'];
	protected $fillable = ['name','name_de','name_fr','name_it','esco_id'];
	protected $table = 'skill_categories';

    public $timestamps = false;

    public function skills () {
        return $this->hasMany('Motibu\Models\Skill', 'skill_category_id');
    }
}
