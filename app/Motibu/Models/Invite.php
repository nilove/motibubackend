<?php
namespace Motibu\Models;

class Invite extends Eloquent {

    protected $fillable = ['code', 'email', 'user_id','user_type'];

}