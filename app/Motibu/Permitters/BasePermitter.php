<?php namespace Motibu\Permitters;

use Motibu\Models\User;

abstract class BasePermitter {

	private $permissions = [];
	private $permissionsRaw = [];
	private $currentRole;
	private $user;
	private $userRoles;
	private $roles;

    /**
     * Initiate permitter with user (typically logged in user)
     * @param User $user
     */
    public function __construct(User $user = null)
	{
		if ($user) $this->setUser($user);
	}

    /**
     * Add a permission definition for a role
     * @param $role
     * @param $permission
     * @param null $verifier
     */
    public function addPermissionForRole ($role, $permission, $verifier = null)
	{
		$normalizedPermission = '^'.str_replace(['.', '*'], ['\.', '.*'], $permission).'$';

		$this->permissions[$role][$normalizedPermission] = $verifier;
		$this->permissionsRaw[$role][] = $permission;
	}

    /**
     * Check permissions for each available role
     * @param $permission
     * @param null $args
     * @return bool
     */
    public function permits ($permission, $args = null)
	{
		foreach ($this->userRoles as $role) {
			if ( ! isset($this->permissions[$role])) continue;
			foreach ($this->permissions[$role] as $permissionPattern => $verifier) {
				if (preg_match('/'.$permissionPattern.'/', $permission)) {
					if ($this->permissions[$role][$permissionPattern] === true) {
						return true;
					} else if (
						call_user_func_array($this->permissions[$role][$permissionPattern], $args) === true
						) {
						return true;
					}
				}
			}
		}

		return false;
	}

    /**
     * Set user and user roles
     * @param $user
     */
    public function setUser ($user)
	{
		$this->user = $user;
		$this->userRoles = $this->user->roles()->lists('name');
	}

    /**
     * List of all permissions
     * @return array
     */
    public function getPermissionsList ()
	{
		return $this->permissionsRaw;
	}

    /**
     * Get user associated with permitter
     * @return mixed
     */
    public function getUser ()
	{
		return $this->user;
	}

    /**
     * Makes grants per role chaianable
     * @param $role
     * @return $this
     */
    public function withRole ($role)
	{
		$this->currentRole = $role;
		return $this;
	}

    /**
     * Grant permissions for selected user role
     * @param array $permissions
     * @return $this
     */
    public function grant (array $permissions)
	{
		foreach ($permissions as $permission => $verifier) {
			$this->addPermissionForRole ($this->currentRole, $permission, $verifier);
		}

		return $this;
	}
}
