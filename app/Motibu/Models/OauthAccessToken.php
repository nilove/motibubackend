<?php namespace Motibu\Models;

use Motibu\Models\User;

class OauthAccessToken extends \Eloquent {

	protected $table = 'oauth_access_tokens';

	public function oauthSession ()
	{
		return $this->belongsTo('Motibu\Models\OauthSession', 'session_id');
	}
}
