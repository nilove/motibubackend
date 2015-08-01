<?php

use Motibu\Models\Message;
use Motibu\Models\User;
use Motibu\Models\Role;
use Motibu\Permitters\Permitter;

class PermissionsTest extends TestCase {

	private $user;

	public function setUp ()
	{
		parent::setUp();

		Artisan::call('migrate');
	}

	protected function tearDown ()
	{
		Mockery::close();
	}

	private function createUserWithRoles ()
	{
		if ($this->user) return $this->user;

		$user = User::create( [
			'username' => 'testUser',
			'email' => 'test@test.com',
			'password' => 'password'
		]);

		with(new RolesTableSeeder)->run();

		$user->roles()->attach(Role::lists('id'));

		$this->user = $user;
		return $user;
	}

	public function testPermissionsCanBeAdded ()
	{
		$user = $this->createUserWithRoles();
		$permitter = new Permitter($user);

		$permitter->withRole('Candidate')
			->grant( [
				'job.apply' => true,
				'job.delete' => function ($random) {
					return false;
				}
			]);

		$this->assertTrue($permitter->getPermissionsList() == [
			'Candidate' => ['job.apply', 'job.delete']
		]);
	}

	public function testPermissionsVerifyProperly ()
	{
		$user = $this->createUserWithRoles();
		$permitter = new Permitter($user);

		$permitter->withRole('Candidate')
			->grant( [
				'job.apply' => true,
				'job.delete' => function ($bool) {
					return $bool;
				}
			]);
			
		$this->assertTrue($permitter->permits('job.apply') === true);
		$this->assertTrue($permitter->permits('job.delete', [false]) === false);
		$this->assertTrue($permitter->permits('job.delete', [true]) === true);
	}

	public function testPermissionsCanBeSetForRoutes ()
	{
		$user = $this->createUserWithRoles();
		\ACL::setUser($user);

		$controller	= Mockery::mock('\ApiController');
		$controller->shouldReceive('tests')->once()->andReturn(\Response::json(['success'=>'great']));
		$controller->shouldReceive('getActionPermissions')->once()->andReturn(['tests' => 'job.apply']);

		\Route::enableFilters();

		\Route::filter('test.permissions.filter', function ($route) {
			require 'app/acl.php';

			$permissions = $route->getAction()['uses'][0]->getActionPermissions();
			$perm = $permissions[$route->getAction()['uses'][1]];
			
			$this->assertTrue(ACL::permits('job.apply', $route->parameters()));
		});

		\Route::get('/test/perms/{id}', [$controller, 'tests'])->after('test.permissions.filter');

		$this->call('GET', '/test/perms/3');

		$this->assertResponseOk();
	}
}
