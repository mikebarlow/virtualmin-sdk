<?php
namespace Snscripts\Virtualmin;

use Snscripts\Virtualmin\Virtualmin;

class Manager
{
    protected $virtualmin;

    /**
     * setup and store the virtualmin details
     *
     * @param \Snscripts\Virtualmin\Virtualmin $virtualmin
     */
    public function __construct(Virtualmin $virtualmin)
    {
        $this->setVirtualmin($virtualmin);
    }

    /**
     * Store the virtualmin connection object
     *
     * @param \Snscripts\Virtualmin\Virtualmin $virtualmin
     *
     * @return \Snscripts\Virtualmin\Manager $this
     */
    public function setVirtualmin(Virtualmin $virtualmin)
    {
        $this->virtualmin = $virtualmin;

        return $this;
    }
}
