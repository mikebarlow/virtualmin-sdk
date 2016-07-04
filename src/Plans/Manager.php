<?php
namespace Snscripts\Virtualmin\Plans;

use Snscripts\Virtualmin\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * list all the available actions for this manager
     */
    protected $actions = [
        'ListPlans' => \Snscripts\Virtualmin\Plans\Actions\ListPlans::class
    ];
}
