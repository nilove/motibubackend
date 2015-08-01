<?php namespace Motibu\Permitters\Definitions;

use Motibu\Permitters\PermissionsInterface;

class JobsControllerPermissions implements PermissionsInterface {
    /**
     * Returns the permission mapping for all the actions on this controller
     * @return array
     */
    public function getPermissions()
    {
        return [
            'create' => ['job.create'],
            'index'  => ['job.read'],
            'delete' => ['job.delete'],
            'update' => ['job.update']
        ];
    }

}
