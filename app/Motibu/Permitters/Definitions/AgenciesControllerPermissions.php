<?php namespace Motibu\Permitters\Definitions;

use Motibu\Permitters\PermissionsInterface;

class AgenciesControllerPermissions implements PermissionsInterface {
    /**
     * Returns the permission mapping for all the actions on this controller
     * @return array
     */
    public function getPermissions()
    {
        return [
            'create' => ['agency.create'],
            'index'  => ['agency.read'],
            'delete' => ['agency.delete'],
            'update' => ['agency.update'],
            'clients' => ['agency.clients'],
            'agents' => ['agency.agents']
        ];
    }

}
