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
        // are we loading an action?
        if (! empty($this->actions[$name])) {
            $this->loadedAction = new $this->actions[$name];

            return $this;
        }

        // are we loading a method on the action?
        if (is_object($this->loadedAction) && method_exists($this->loadedAction, $name)) {
            call_user_func_array(
                [$this->loadedAction, $name],
                $params
            );

            return $this;
        }

        // still nothing, exception
        throw new \Snscripts\Virtualmin\Exceptions\UnrecognisedAction($name . ' Action is not recognised on ' . get_class($this));
    }

    /**
     * run the loaded action
     *
     * @return mixed
     */
    public function run()
    {
        if (! is_object($this->loadedAction)) {
            throw new \Snscripts\Virtualmin\Exceptions\NoActionLoaded('No action was loaded for ' . get_class($this));
        }

        $Query = $this->virtualmin->getHttp();

        // build up the request
        $results = $Query->request(
            $this->loadedAction->getMethodType(),
            $this->virtualmin->buildUrl(
                $this->loadedAction->getProgramName(),
                $this->loadedAction->getQueryParams()
            ),
            [
                'auth' => [
                    $this->virtualmin->user,
                    $this->virtualmin->pass
                ]
            ]
        );

        if ($results->getStatusCode() === 200) {
            return $this->loadedAction->processResults($results);
        }

        // do some kind of error
    }
}
