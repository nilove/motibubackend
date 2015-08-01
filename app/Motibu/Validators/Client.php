<?php namespace Motibu\Validators;

use Laracasts\Validation\FormValidator;

class Client extends FormValidator {

    protected $rules = [
        'name' => 'required',
        'agency_id' => 'required'
    ];

}
