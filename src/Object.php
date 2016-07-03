<?php
namespace Snscripts\Virtualmin;

class Object
{
    protected $data;

    /**
     * Magic Getter method for retrieving object data
     *
     * @param string $var Variable to get
     * @return mixed|null
     */
    public function __get($var)
    {
        // check for a getter method
        $methodName = 'get' . ucfirst($var);
        if (method_exists($this, $methodName)) {
            return $this->$methodName();
        }

        // get the data error
        if (array_key_exists($var, $this->data)) {
            return $this->data[$var];
        }

        return null;
    }

    /**
     * Magic Setter method for setting data
     *
     * @param string $var Variable to set
     * @param mixed $value The value to set
     * @return mixed
     */
    public function __set($var, $value)
    {
        // check for a getter method
        $methodName = 'set' . ucfirst($var);
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        }

        $this->data[$var] = $value;
        return true;
    }

    /**
     * function overloading to allow twig to access the attributes
     *
     * @param string $name method name we are trying to load
     * @param array $params array of params to pass to the method
     * @return mixed $result return of the method
     */
    public function __call($name, $params)
    {
        if (strtolower(substr($name, 0, 3)) === 'set') {
            $var = strtolower(substr($name, 3));

            $value = '';
            if (isset($params['0'])) {
                $value = $params['0'];
            }

            $this->data[$var] = $value;

            return;
        } elseif (strtolower(substr($name, 0, 3)) === 'get') {
            $var = strtolower(substr($name, 3));

            if (isset($this->data[$var])) {
                return $this->data[$var];
            }

            return false;
        }

        return false;
    }
}
