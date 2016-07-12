<?php
namespace Snscripts\Virtualmin\Plans\Actions;

use Snscripts\Virtualmin\Base\AbstractAction;
use Snscripts\Virtualmin\Plans\Plan;

class ListPlans extends AbstractAction
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
        return 'list-plans';
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
        $Collection = new \Cartalyst\Collections\Collection;

        if ($this->validate($results) && $this->isSuccess($results)) {
            foreach ($results['data'] as $item) {
                if (strtolower($item['values']['maximum_bw']['0']) === 'unlimited') {
                    $bandwidth = 'Unlimited';
                } else {
                    $bandwidth = ($item['values']['maximum_bw']['0'] / 1073741824) . ' GB';
                }

                if (isset($item['values']['server_quota']['0'])) {
                    $diskSpace = $item['values']['server_quota']['0'];
                } else {
                    $diskSpace = 'Unlimited';
                }

                $Plan = new Plan;
                $Plan->fill([
                    'id' => $item['name'],
                    'name' => $item['values']['name']['0'],
                    'bandwidth' => $bandwidth,
                    'disk_space' => $diskSpace
                ]);

                $Collection->put(
                    $Plan->id,
                    $Plan
                );
            }
        }

        return $Collection;
    }
}
