<?php
namespace Snscripts\Virtualmin\Other;

use Snscripts\Virtualmin\Base\Manager as BaseManager;

class Manager extends BaseManager
{
    /**
     * list all the available actions for this manager
     */
    protected $actions = [
        'GetInfo' => '\Snscripts\Virtualmin\Other\Actions\GetInfo'
    ];
}
