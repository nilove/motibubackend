<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Subscription extends \Eloquent {
    use SoftDeletingTrait;

    protected $fillable = ['user_id', 'plan_id', 'expires_on', 'metadata'];

    public function plan()
    {
    	return $this->belongsTo('Motibu\Models\Plan');
    }

    public function user()
    {
    	return $this->belongsTo('Motibu\Models\User');
    }
}
