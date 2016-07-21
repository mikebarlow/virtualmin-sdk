<?php
namespace Snscripts\Virtualmin\Base;

class Object
{
    protected $data;

    /**
     * mass fill data from array
     *
     * @param array $data
     * @return Object $this
     */
    public function fill($data)
    {
        array_walk($data, function ($value, $key) {
            $this->data[$key] = $value;
        });

        return $this;
    }

    /**
     * Magic Getter method for retrieving object data
     *
     * @param string $var Variable to get
     * @return mixed|null
     */
    public function __get($var)
    {
        // check for a getter method
        $methodName = 'get' . $this->convertVarToFunc($var);
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
     * @return void
     */
    public function __set($var, $value)
    {
        // check for a getter method
        $methodName = 'set' . $this->convertVarToFunc($var);
        if (method_exists($this, $methodName)) {
            $this->$methodName($value);
        }

        $this->data[$var] = $value;
    }

    /**
     * Magic methods for check items isset / empty
     *
     * @param string $var Variable to set
     * @return bool
     */
    public function __isset($var)
    {
        return isset($this->data[$var]);
    }

    /**
     * function overloading to allow dynamic get / set
     *
     * @param string $name method name we are trying to load
     * @param array $params array of params to pass to the method
     * @return mixed $result return of the method or the object instance
     */
    public function __call($name, $params)
    {
        if (strtolower(substr($name, 0, 3)) === 'set') {
            $var = $this->convertFuncToVar(substr($name, 3));

            $value = '';
            if (isset($params['0'])) {
                $value = $params['0'];
            }

            $this->data[$var] = $value;

            return $this;
        } elseif (strtolower(substr($name, 0, 3)) === 'get') {
            $var = $this->convertFuncToVar(substr($name, 3));

            if (array_key_exists($var, $this->data)) {
                return $this->data[$var];
            }

            return false;
        }

        return false;
    }

    /**
     * convert a magic method into a variable name for storing
     * essential camelCase to snake_case
     *
     * @param string $func
     * @return string $var
     */
    public function convertFuncToVar($func)
    {
        $var = ltrim(strtolower(preg_replace('/[A-Z]/', '_$0', $func)), '_');

        return $var;
    }

    /**
     * convert a variable name into a magic method
     * essentially snake_case to camelCase
     *
     * @param string $func
     * @return string $var
     */
    public function convertVarToFunc($var)
    {
        $str = explode('_', strtolower($var));
        array_walk($str, function (&$value, $key) {
            $value = ucfirst(strtolower($value));
        });

        $func = implode('', $str);

        return $func;
    }
}
