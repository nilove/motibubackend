<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Laracasts\Presenter\PresentableTrait;

class Agent extends \Eloquent {
    use SoftDeletingTrait, PresentableTrait;

    protected $presenter = "Motibu\Presenters\AgentPresenter";

    protected $fillable = ['name', 'description', 'telephone', 'profile_pic_filename', 'agency_id', 'user_id'];

    public function getJobsAttribute()
    {
        return $this->user->jobs;
    }

    public function user ()
    {
        return $this->belongsTo('Motibu\Models\User');
    }
}
