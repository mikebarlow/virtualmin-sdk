<?php
namespace Snscripts\Virtualmin\Hosting\Actions;

use Snscripts\Virtualmin\Base\AbstractAction;
use Snscripts\Virtualmin\Hosting\Domain;

class ListServices extends AbstractAction
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
        return 'list-domains';
    }

    /**
     * build the query string / data into array
     *
     * @return array $queryParams
     */
    public function getQueryParams()
    {
        return $this->data;
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
                $itemData = [
                    'name' => $item['name']
                ];

                foreach ($item['values'] as $key => $value) {
                    if (count($value) > 1) {
                        $itemData[$key] = new \Cartalyst\Collections\Collection($value);
                    } else {
                        $itemData[$key] = $value['0'];
                    }
                }

                $Domain = new Domain;
                $Domain->fill($itemData);

                $Collection->put(
                    $Domain->id,
                    $Domain
                );
            }
        }

        if ($Collection->count() === 1) {
            return $Collection->first();
        }

        return $Collection;
    }
}
