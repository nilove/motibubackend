<?php namespace Motibu\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class AgencyContact extends \Eloquent {
    use SoftDeletingTrait;
    protected $table = 'agency_contacts';

    protected $fillable = ['name', 'department', 'email', 'telephone', 'fax'];

    public function agency()
    {
        return $this->bolongsTo('Motibu\Models\Agency');
    }
}
