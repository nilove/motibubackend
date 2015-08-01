<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Laracasts\Presenter\PresentableTrait;

class Agency extends \Eloquent {
    use SoftDeletingTrait, PresentableTrait;

    protected $presenter = 'Motibu\Presenters\AgencyPresenter';

    protected $fillable = ['name', 'description'];

    protected $with = ['industry'];

    public function clients ()
    {
        return $this->hasMany('Motibu\Models\Client');
    }

    public function jobs ()
    {
    	return $this->hasMany('Motibu\Models\Job');
    }

    public function agents ()
    {
    	return $this->hasMany('Motibu\Models\Agent');
    }

    public function admins ()
    {
        return $this->belongsToMany('Motibu\Models\User', 'user_to_agency');
    }

    public function industry ()
    {
    	return $this->belongsTo('Motibu\Models\Industry');
    }

    public function hasAdmin($user)
    {
		return ! $this->admins->filter(function($nthUser) use ($user) {
	        return $nthUser->id == $user->id;
	    })->isEmpty();
    }
}
