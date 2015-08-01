<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Message extends \Eloquent {
    use SoftDeletingTrait;

    protected $fillable = ['sender_id', 'recipient_id', 'payload', 'job_id'];

	public function send ($senderId, $recipientId, $payload)
	{
		$this->sender_id = $senderId;
		$this->recipient_id = $recipientId;
		$this->payload = $payload;

		return $this->save();
	}

	public function sender () {
		return $this->belongsTo('\Motibu\Models\User', 'sender_id');
	}

}
