<?php
namespace Snscripts\Virtualmin;

interface ActionInterface
{
    /**
     * return the method type to use
     *
     * @return string $method
     */
    public function getMethodType();

    /**
     * return the program name / endpoint
     *
     * @return string $program The endpoint program
     */
    public function getProgramName();

    /**
     * build the query string / data into array
     *
     * @return array $queryParams
     */
    public function getQueryParams();

    /**
     * process the results from the query
     *
     * @param json $results JSON results from the call
     * @return mixed
     */
    public function processResults($results);
}
