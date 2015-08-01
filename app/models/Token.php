<?php

class Token extends Eloquent
{

    protected $table = 'tokens';

    protected $fillable = ['api_token', 'client', 'user_id', 'expires_on'];


    public function scopeValid()
    {
        return !Carbon\Carbon::createFromTimeStamp(strtotime($this->expires_on))->isPast();
    }

    public function user()
    {
        return $this->belongsTo('Motibu\Models\User', 'user_id');
    }
}