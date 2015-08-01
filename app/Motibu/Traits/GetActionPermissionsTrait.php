<?php namespace Motibu\Traits;

trait GetActionPermissionsTrait {

	public function getActionPermissions ($controllerClass)
	{
		$class = 'Motibu\\Permitters\\Definitions\\'.$controllerClass.'Permissions';
		$permissionsObject = new $class;
		if ($permissionsObject instanceof \Motibu\Permitters\PermissionsInterface)
			return $permissionsObject->getPermissions();
		else
			dd('errarara');
	}
}