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
     * I'm ashamed but Virtualmin API doesn't leave you a lot to work with
     * for this endpoint
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
            $subSection = '';

            // loop each rpw
            foreach ($items as $itemKey => $item) {
                $value = $item;
                if (strpos($item, ':') !== false) {
                    list($key, $value) = explode(':', $item, 2);
                }

                // this is to try and weed out empty elements
                // and also catch '0' values and turn them into ints
                // so we can check they exist properly
                $strippedValue = trim(str_replace('*', '', $value));
                if (is_numeric($strippedValue)) {
                    $strippedValue = $value = intval($strippedValue);
                }

                // if we have a key, but no "value" its likely to be an element
                // with sub values
                if (! empty($key) && empty($strippedValue) && $strippedValue !== 0) {
                    $info[$key] = [];
                    $subSection = $key;
                } else {
                    // attempt to clean up key for array element
                    $arrayKey = trim(
                        str_replace('*', '', (! empty($key) ? $key : $itemKey))
                    );

                    // check for sub item
                    // filthy checks for sub items #Sorry
                    if (strpos($item, '        ') === 0 && ! empty($subSection) && ! empty($subSubSection)) {
                        // second level
                        $info[$subSection][$subSubSection][$arrayKey] = $strippedValue;
                    } elseif (strpos($item, '    ') === 0 && ! empty($subSection)) {
                        // first level
                        if (! empty($strippedValue)) {
                            $val = $strippedValue;
                        } else {
                            $val = [];
                            $subSubSection = $arrayKey;
                        }

                        $info[$subSection][$arrayKey] = $val;
                    } else {
                        if (empty($strippedValue)) {
                            continue;
                        }

                        $info[$arrayKey] = $strippedValue;
                    }
                }

                unset($key, $value);
            }

            $VirtualminServer->fill($info);
        }

        return $VirtualminServer;
    }
}
