<?php namespace Motibu\Models;


class Address extends \Eloquent {

    public $timestamps = false;

    public function addressable(){
        return $this->morphTo();
    }
}
