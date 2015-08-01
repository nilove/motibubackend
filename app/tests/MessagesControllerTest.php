<?php

use Motibu\Models\Message;

class MessagesControllerTest extends TestCase {

	public function setUp ()
	{
		parent::setUp();

		Artisan::call('migrate');
	}

	protected function tearDown ()
	{
		Mockery::close();
	}

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testMessagesSendRequestOk()
	{
		Auth::shouldReceive('user')->once()->andReturn((object)['id'=>1]);
		$this->call('POST', '/v1/messages/send', ['recipient_id' => '1', 'payload' => 'daljsdsakj']);
		$this->assertCount(1, Message::all());
		$this->assertResponseOk();
	}

}
