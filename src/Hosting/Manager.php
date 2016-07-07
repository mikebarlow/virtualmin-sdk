<?php
namespace Snscripts\Virtualmin\Hosting;

use Snscripts\Virtualmin\Base\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * list all the available actions for this manager
     */
    protected $actions = [
        'CreateService' => '\Snscripts\Virtualmin\Hosting\Actions\CreateService',
        'DeleteService' => '\Snscripts\Virtualmin\Hosting\Actions\DeleteService',
        'EnableService' => '\Snscripts\Virtualmin\Hosting\Actions\EnableService',
        'DisableService' => '\Snscripts\Virtualmin\Hosting\Actions\DisableService'
    ];
}
