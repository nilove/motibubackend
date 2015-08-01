<?php namespace Motibu\Models;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
// use Eloquent, Hash;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Laracasts\Presenter\PresentableTrait;


/**
 * Class User
 * @package Motibu\Models
 */
class User extends \Eloquent implements UserInterface, RemindableInterface
{


    use UserTrait, RemindableTrait, PresentableTrait, SoftDeletingTrait;

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [ 'username', 'email', 'password', 'first_name', 'last_name', 'confirmation_code' ];

    protected $with = ['meta'];

    /**
     * Path to the presenter for a user
     * @var string
     */
    protected $presenter = 'Motibu\Users\UserPresenter';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token','confirmed', 'confirmation_code', 'userable', 'userable_type', 'remember_token', 'updated_at', 'created_at');

    /**
     * Passwords must always be hashed
     * @param $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = \Hash::make($password);
    }

    /**
     * A user has many companeis
     * @return mixed
     */
    public function clients()
    {
        return $this->hasMany('Motibu\Models\Client');
    }

    /**
     * A user has many agencies
     * @return mixed
     */
    public function agencies()
    {
        return $this->belongsToMany('Motibu\Models\Agency', 'user_to_agency');
    }


    /**
     * Return the tokens for the current user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tokens()
    {
        return $this->hasMany('Token');
    }
    /**
     * A user can have many roles
     * @return mixed
     */
    public function roles()
    {
        return $this->belongsToMany('Motibu\Models\Role');
    }

    /**
     * A user as agent can have many jobs
     * @return mixed
     */
    public function jobs()
    {
        return $this->belongsToMany('Motibu\Models\Job', 'agent_to_job');
    }

    /**
     * A user as agent can have many jobs
     * @return mixed
     */
    public function jobsAppliedTo()
    {
        return $this->belongsToMany('Motibu\Models\Job', 'candidate_to_job');
    }

    /**
     * Register a new user
     *
     * @static
     * @param $username
     * @param $email
     * @param $password
     * @return static
     */
    public static function register($username, $email, $password)
    {
        $user = new static( compact('username', 'email', 'password') );

        $user->raise(new UserRegistered($user));

        return $user;
    }

    public function candidateProfile ()
    {
        return $this->hasOne('Motibu\Models\Candidate');
    }

    public function agentProfile ()
    {
        return $this->hasOne('Motibu\Models\Agent');
    }

    public function MotiCardProfile ()
    {
        return $this->hasOne('Motibu\Models\Moticardtoken');
    }

    public function skills ()
    {
        return $this->belongsToMany('Motibu\Models\Skill', 'candidate_to_skill')->withPivot('description', 'level');
    }

    public function subscriptions ()
    {
        return $this->hasMany('Motibu\Models\Subscription');
    }

    public function meta ()
    {
        return $this->hasOne('Motibu\Models\Usermeta');
    }

    public function getMeta ()
    {
        if ($this->meta) {
            return $this->meta;
        } else {
            $meta = Usermeta::create(['user_id' => $this->id]);
            return $meta;
        }
    }

    public function setMeta ($newValues)
    {
        $meta = $this->getMeta();
        return $meta->update($newValues);
    }

    /** 
     * Determine if the given user is the same
     * as the current one
     * @param $user
     * @return bool
     */
    public function is($user)
    {
        if(is_null($user)) return false;
        return $this->username == $user->username;
    }

    public function userable(){
        return $this->morphTo();
    }

    public function candidate(){
        return $this->morphedByMany('Motibu\Models\Candidate','userable');
    }

}
