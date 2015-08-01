<?php
// filename:: app/models/Oauthscope.php

class Oauthscope extends Eloquent
{

    protected $guarded = [];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'oauth_scopes';


    public $timestamps = false;

}