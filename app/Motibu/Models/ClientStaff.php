<?php namespace Motibu\Models;

use Laracasts\Presenter\PresentableTrait;
use Motibu\Models\Client;

class ClientStaff extends \Eloquent {
	use PresentableTrait;

	protected $presenter = 'Motibu\Presenters\ClientStaffPresenter';

	protected $table = 'client_staff';
	protected $fillable = ['name', 'telephone', 'email', 'client_id', 'type'];
	
	public function client ()
	{
		return $this->belongsTo('Motibu\Models\Client');
	}
}
