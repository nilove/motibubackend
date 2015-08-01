<?php namespace Motibu\Models;

use Motibu\Models\User;

class OauthSession extends \Eloquent {

	protected $table = 'oauth_sessions';

	public function user ()
	{
		return $this->belongsTo('Motibu\Models\User', 'owner_id');
	}
}
