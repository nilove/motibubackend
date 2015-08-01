<?php namespace Motibu\Permitters\Definitions;

use Motibu\Permitters\PermissionsInterface;

class HomeControllerPermissions implements PermissionsInterface {
	
	public function getPermissions ()
	{
		return [
			'showWelcome' => ['hellothere']
		];
	}
}
