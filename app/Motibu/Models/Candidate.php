<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Laracasts\Presenter\PresentableTrait;

class Candidate extends \Eloquent {
    use SoftDeletingTrait, PresentableTrait;

    protected $presenter = '\Motibu\Presenters\CandidatePresenter';

    protected $table = "candidates";
    protected $fillable = ['user_id', 'gender_id','date_of_birth', 'about', 'residency', 'telephone', 'mobile',
        'years_of_experience', 'nationality', 'has_work_permit', 'is_married',
        'children', 'drivers_license', 'is_available', 'is_employed','has_drivers_license', 'social_facebook',
        'social_linked_in', 'social_twitter', 'social_google_plus', 'social_instagram', 'social_youtube', 'inline_skills',
        'location_name', 'location_latitude', 'location_longitude','num_children','age','expected_salary','is_external_vcard'];

    public function user ()
    {
        return $this->belongsTo('Motibu\Models\User');
    }

    public function address(){
        return $this->morphOne('Motibu\Models\Address','addressable');
    }

    public function jobs()
    {

    }
}
