<?php
namespace Snscripts\Virtualmin;

use Snscripts\Virtualmin\Base\VMObject;
use GuzzleHttp\ClientInterface;

class Virtualmin extends VMObject
{
    const
        VERIFY   = true,
        NOVERIFY = false,
        SECURE   = true,
        NOSECURE = false;

    /**
     * set up Virtualmin Config
     *
     * @param VMObject $http Guzzle Package
     */
    public function __construct(ClientInterface $http)
    {
        $this->data['http'] = $http;
        $this->data['verify'] = self::VERIFY;
    }

    /**
     *
     * @param string $host The host to connect to
     * @param string $user The main account username
     * @param string $pass The main account password
     * @param bool $secure Use a secure connection?
     *
     * @return Virtualmin $this
     */
    public function setConnection($host, $user, $pass, $secure = true)
    {
        $this->setHost($host, $secure);

        $this->data['user'] = $user;
        $this->data['pass'] = $pass;

        return $this;
    }

    /**
     * setter for host variable
     *
     * @param string $host The virtualmin host
     * @param bool $secure Use a secure connection?
     *
     * @return Virtualmin $this
     */
    public function setHost($host, $secure)
    {
        if (strpos($host, '://') !== false) {
            $hostBits = explode('://', $host, 2);

            $host = $hostBits[1];
        }

        $this->data['host'] = (isset($secure) && $secure ? 'https://' : 'http://') . $host;

        return $this;
    }

    /**
     * build up the url to use for query
     *
     * @param string $programName
     * @param array $urlParams
     *
     * @return string
     */
    public function buildUrl($programName, $urlParams = [])
    {
        $url = rtrim($this->data['host'], '/') . '/virtual-server/remote.cgi';

        $urlParams['program'] = $programName;
        $urlParams['json'] = 1;
        $urlParams['multiline'] = '';

        $urlParams = $this->convertParamKeys($urlParams);

        return $url . '?' . http_build_query($urlParams);
    }

    /**
     * convert array keys into valid virtualmin params
     *
     * @param array $urlParams
     * @return array $newUrlParams
     */
    public function convertParamKeys($urlParams)
    {
        $newUrlParams = [];
        foreach ($urlParams as $key => $value) {
            $newUrlParams[str_replace('_', '-', $key)] = $value;
        }

        return $newUrlParams;
    }
}
