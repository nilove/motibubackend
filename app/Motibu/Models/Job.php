<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Job extends \Eloquent {

    use SoftDeletingTrait;

	protected $guarded = ['id'];
	protected $fillable = ['agency_id', 'client_id', 'agent_id','hr_id', 'title','title_is_private', 'about','about_is_private','salary_range_is_private', 'salary_range_from', 'salary_range_to',
        'mandate_start','mandate_end','mandate_is_private','is_published','sector_id','sector_id_is_private','age_range_from','age_range_to','age_range_is_private','gender_id','gender_id_is_private','nationality_id',
        'nationality_id_is_private','work_permit_id_is_private','work_permit_id','years_of_experience','years_of_experience_is_private','min_degree_id','min_degree_id_is_private','residence_id','residence_id_is_private','date_of_entry','date_of_entry_is_private','working_hours_from',
        'working_hours_to','working_hours_is_private','slug', 'inline_skills',
        'location_name', 'location_latitude', 'location_longitude'];

    public function client () {
        return $this->belongsTo('Motibu\Models\Client');
    }

    public function hr () {
        return $this->belongsTo('Motibu\Models\ClientStaff');
    }

    public function agent () {
        return $this->belongsTo('Motibu\Models\User', 'agent_id');
    }

    public function candidates () {
        return $this->belongsToMany('Motibu\Models\User', 'candidate_to_job');
    }

    public function agency () {
        return $this->belongsTo('Motibu\Models\Agency');
    }

    public function skills ()
    {
    	return $this->belongsToMany('Motibu\Models\Skill', 'skill_to_job')->withPivot('description', 'level');
    }
}
