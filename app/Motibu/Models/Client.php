<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Laracasts\Presenter\PresentableTrait;

class Client extends \Eloquent {
    use SoftDeletingTrait, PresentableTrait;

    protected $presenter = 'Motibu\Presenters\ClientPresenter';

    protected $fillable = ['name', 'about', 'contact_name', 'contact_telephone', 'contact_email', 'agency_id', 'industry_id'];

    public function jobs ()
    {
        return $this->hasMany('Motibu\Models\Job');
    }

    public function owner(){
        return $this->belongsTo('Motibu\Models\User');
    }

    public function address(){
        return $this->morphOne('Motibu\Models\Address','addressable');
    }

    public function staff()
    {
        return $this->hasMany('Motibu\Models\ClientStaff');
    }
}
