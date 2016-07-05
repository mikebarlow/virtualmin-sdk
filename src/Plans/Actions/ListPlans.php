<?php
namespace Snscripts\Virtualmin\Plans\Actions;

use Snscripts\Virtualmin\AbstractAction;

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
        $Collection = new \Cake\Collection\Collection([]);

        if ($this->validateResults($results)) {
            foreach ($results['data'] as $item) {
                $Plan = new Plan;
                $Plan->fill([
                    'id' => $item['name'],
                    'name' => $item['values']['name']['0'],
                    'bandwidth' => ($item['values']['maximum_bw']['0'] / 1073741824) . ' GB',
                    'disk_space' => $item['values']['server_quota']['0']
                ]);

                $Collection->append([
                    $Plan->id => $Plan
                ]);
            }
        }

        return $Collection;
    }
}
