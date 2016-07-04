<?php
namespace Snscripts\Virtualmin;

use Snscripts\Virtualmin\Virtualmin;

class Manager
{
    protected $virtualmin;
    protected $loadedAction;

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

    /**
     * function overloading to allow loading of actions
     *
     * @param string $name method name we are trying to load
     * @param array $params array of params to pass to the method
     * @return self $$this return current object
     */
    public function __call($name, $params)
    {
        if (! empty($this->actions[$name])) {
            $this->loadedAction = new $this->actions[$name];

            return $this;
        }

        $class = get_class($this);
        throw new \Snscripts\Virtualmin\Exceptions\UnrecognisedAction($name . ' Action is not recognised on ' . $class);
    }
}
