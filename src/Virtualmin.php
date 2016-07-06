<?php
namespace Snscripts\Virtualmin;

use Snscripts\Virtualmin\Base\Object;
use GuzzleHttp\ClientInterface;

class Virtualmin extends Object
{
    const
        VERIFY = true,
        NOVERIFY = false;

    /**
     * set up Virtualmin Config
     *
     * @param Object $http Guzzle Package
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

        return $url . '?' . http_build_query($urlParams);
    }
}
