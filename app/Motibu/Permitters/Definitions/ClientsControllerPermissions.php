<?php namespace Motibu\Permitters\Definitions;

use Motibu\Permitters\PermissionsInterface;

class ClientsControllerPermissions implements PermissionsInterface {
    /**
     * Returns the permission mapping for all the actions on this controller
     * @return array
     */
    public function getPermissions()
    {
        return [
            'create' => ['client.create'],
            'show'  => ['client.show'],
            // 'index'  => ['agency.read'],
            // 'delete' => ['agency.delete'],
            // 'update' => ['agency.update'],
            // 'clients' => ['agency.clients']
            'clientstaff' => ['staff.read']
        ];
    }

}
