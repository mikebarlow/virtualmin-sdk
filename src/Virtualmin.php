<?php
namespace Snscripts\Virtualmin;

class Virtualmin extends Object
{
    /**
     * set up Virtualmin Config
     *
     * @param string $host The host to connect to
     * @param string $user The main account username
     * @param string $pass The main account password
     * @param bool $secure Use a secure connection?
     */
    public function __construct($host, $user, $pass, $secure = true)
    {
        $this->setHost($host, $secure);

        $this->user = $user;
        $this->pass = $pass;
    }

    /**
     * setter for host variable
     *
     * @param string $host The virtualmin host
     * @param bool $secure Use a secure connection?
     */
    public function setHost($host, $secure)
    {
        if (strpos($host, '://') !== false) {
            $hostBits = explode('://', $host, 2);

            $host = $hostBits[1];
        }

        $this->host = (isset($secure) && $secure ? 'https://' : 'http://') . $host;
    }
}
