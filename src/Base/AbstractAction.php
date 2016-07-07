<?php
namespace Snscripts\Virtualmin\Base;

abstract class AbstractAction
{
    /**
     * return the method type to use
     *
     * @return string $method
     */
    abstract public function getMethodType();

    /**
     * return the program name / endpoint
     *
     * @return string $program The endpoint program
     */
    abstract public function getProgramName();

    /**
     * build the query string / data into array
     *
     * @return array $queryParams
     */
    abstract public function getQueryParams();

    /**
     * process the results from the query
     *
     * @param json $results JSON results from the call
     *
     * @return mixed
     */
    abstract public function processResults($results);

    /**
     * validate the results
     *
     * @param json $results
     *
     * @return bool
     */
    public function validate($results)
    {
        return isset($results['command']) && $results['command'] === $this->getProgramName();
    }

    /**
     * successful result
     *
     * @param json $results
     *
     * @return bool
     */
    public function isSuccess($results)
    {
        return isset($results['status']) && $results['status'] === 'success';
    }
}
