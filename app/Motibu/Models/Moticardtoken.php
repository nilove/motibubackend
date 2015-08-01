<?php namespace Motibu\Models;


class Moticardtoken extends \Eloquent {

	protected $table = 'moticardtoken';
	protected $fillable = ['user_id','token'];

	public function user ()
	{
		return $this->belongsTo('Motibu\Models\User');
	}
}
