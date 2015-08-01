<?php namespace Motibu\Permitters;

use Illuminate\Support\Facades\Facade;

class PermitterFacade extends Facade {

	/**
	 * Get the registered name of the component.
	 *
	 * @return string
	 */
	protected static function getFacadeAccessor() { return 'permitter'; }

}
