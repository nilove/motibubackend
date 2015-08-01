<?php

use Motibu\Models\Agency;
use Motibu\Models\Role;
use Motibu\Models\User;
// use Motibu\Traits\GetActionPermissionsTrait;

class HomeController extends BaseController {

	// use GetActionPermissionsTrait;

	public function showWelcome()
	{
		Auth::login(User::find(1));
		dd(Auth::user()->hasMtmRole(Role::findByName('Agency Admin')));
		dd(Agency::findByName('Dietrich, Koelpin and Weissnat'));
		$model = Agency::first();
		echo $model->hasMtmUser(User::find(1), 'admins');
		// dd($this->get_class());
		// dd($this->getActionPermissions(get_class($this)));
		
		// return View::make('hello');
	}

}
