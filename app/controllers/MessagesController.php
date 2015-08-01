<?php

use Motibu\Models\Message;

class MessagesController extends \BaseController {

	public function __construct (Message $message)
	{
		$this->message = $message;
	}
	
	public function send ()
	{
		$recipientId = \Input::get('recipient_id');
		$payload = \Input::get('payload');
		return \Response::json( [
				'success' => $this->message->send (\Auth::user()->id, $recipientId, $payload)
			]
		);
	}
}
