<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Industry extends \Eloquent {

    // use SoftDeletingTrait;

	protected $guarded = ['id'];
	protected $fillable = ['name'];


    public function clients () {
        return $this->hasMany('Motibu\Models\Client');
    }
}
