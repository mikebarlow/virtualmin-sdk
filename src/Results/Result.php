<?php
namespace Snscripts\Virtualmin\Results;

use Snscripts\Virtualmin\Base\Object;

class Result extends Object
{
    const SUCCESS = true;
    const FAIL = false;

    /**
     * construct
     * Set the initia status to self::FAIL
     */
    public function __construct()
    {
        $this->data['status'] = self::FAIL;
    }
}
