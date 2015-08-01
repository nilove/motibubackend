<?php namespace Motibu\Validators;

use Laracasts\Validation\FormValidator;

class Jobs extends FormValidator {

	protected $rules = [
		'agency_id' => 'required',
		'agent_id' => 'required',
        'client_id' => 'required',
        'hr_id' => 'required',
		'title' => 'required',
		'about' => 'required'
	];

}
