<?php namespace Motibu\Models;

class Usermeta extends \Eloquent {

	protected $guarded = ['id'];
	protected $table = 'user_meta';
	protected $fillable = ['user_id', 'last_message_read', 'last_notification_seen'];

	public function user()
	{
		return $this->belongsTo('Motibu\Models\User');
	}
}
