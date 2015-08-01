<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Plan extends \Eloquent {
    use SoftDeletingTrait;

    protected $fillable = ['title', 'description', 'duration', 'cost_in_cents', 'meta'];
}
