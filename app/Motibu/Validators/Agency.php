<?php

namespace Motibu\Validators;


use Laracasts\Validation\FormValidator;

class Agency extends FormValidator {

    protected $rules = [
        'name'          => 'required',
        'description'   => 'required'
    ];
} 