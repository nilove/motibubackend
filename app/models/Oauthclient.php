<?php

// Filenname::app/models/Oauthclient.php
class Oauthclient extends Eloquent
{

    protected $guarded = [];

    /**
    * The database table used by the model.
    *
    * @var string
    */
    protected $table = 'oauth_clients';

    public $timestamps = false;

}