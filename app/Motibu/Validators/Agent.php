<?php namespace Motibu\Validators;

use Laracasts\Validation\FormValidator;

class Agent extends FormValidator {

    protected $rules = [
        'name' => 'required',
        'agency_id' => 'required'
    ];

}
