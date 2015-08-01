<?php

use Motibu\Models\Message;

class JobsControllerTest extends TestCase {

	public function setUp ()
	{
		parent::setUp();

		Artisan::call('migrate');
		// $this->seed();
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
	public function testJobsValidatorFailsWithInvalidInput()
	{
		$this->setExpectedException('Laracasts\Validation\FormValidationException');
		$this->call('POST', '/v1/jobs/create', []);
	}

	public function testJobsValidatorWorksWithValidInput()
	{
		$this->call('POST', '/v1/jobs/create', [
			'company_id' => 1,
			'title' => 'summin',
			'description' => 'summin else'
		]);
		$this->assertResponseOk();
	}

	public function testRolesAreSeededProperly()
	{
		// dd(Motibu\Models\Role::lists('name'));
		$this->assertTrue(true);
	}

}
