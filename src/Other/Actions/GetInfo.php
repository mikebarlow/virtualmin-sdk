<?php
namespace Snscripts\Virtualmin\Other\Actions;

use Snscripts\Virtualmin\Base\AbstractAction;
use Snscripts\Virtualmin\Other\VirtualminServer;

class GetInfo extends AbstractAction
{
    /**
     * return the method type to use
     *
     * @return string $method
     */
    public function getMethodType()
    {
        return 'get';
    }

    /**
     * return the program name / endpoint
     *
     * @return string $program The endpoint program
     */
    public function getProgramName()
    {
        return 'info';
    }

    /**
     * build the query string / data into array
     *
     * @return array $queryParams
     */
    public function getQueryParams()
    {
        return [];
    }

    /**
     * process the results from the query
     *
     * @param json $results JSON results from the call
     * @return mixed
     */
    public function processResults($results)
    {
        $VirtualminServer = new \Snscripts\Virtualmin\Other\VirtualminServer;

        if ($this->validate($results) && $this->isSuccess($results)) {
            $info = [];
            $serverInfo = $results['output'];
            $items = explode("\n", $serverInfo);
            array_walk($items, function ($item, $itemKey, &$info) {
                list($key, $value) = explode(':', $item, 2);

                $info[$key] = $value;
            }, $info);

            $VirtualminServer->fill($info);
        }

        return $VirtualminServer;
    }
}
