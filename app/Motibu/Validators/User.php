<?php namespace Motibu\Validators;

use Laracasts\Validation\FormValidator;

class User extends FormValidator {

    protected $rules = [
        'username' => 'min:6|unique:users',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:6',
        'confirm_password' => 'same:password'
    ];

}
