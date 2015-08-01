<?php namespace Motibu\Validators;

use Laracasts\Validation\FormValidator;

class Candidate extends FormValidator {

    protected $rules = [
        'user_id' => 'required',
    ];

}
