<?php
namespace Snscripts\Virtualmin\Hosting\Actions;

use Snscripts\Virtualmin\Base\AbstractAction;
use Snscripts\Virtualmin\Results\Result;

class EnableService extends AbstractAction
{
    /**
     * return the method type to use
     *
     * @return string $method
     */
    public function getMethodType()
    {
        return 'post';
    }

    /**
     * return the program name / endpoint
     *
     * @return string $program The endpoint program
     */
    public function getProgramName()
    {
        return 'enable-domain';
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
        $Result = new Result;

        if ($this->validate($results)) {
            if ($this->isSuccess($results)) {
                $Result->setStatus(Result::SUCCESS);
                $Result->setMessage('Hosting service enabled successfully');
            } else {
                if (! empty($results['error'])) {
                    $Result->setMessage($results['error']);
                } else {
                    $Result->setMessage('An unknown error occurred.');
                }
            }
        } else {
            $Result->setMessage('An invalid request was made.');
        }

        return $Result;
    }
}
