<?php


namespace IsaEken\NetGSM;


use Exception;
use Illuminate\Support\Str;

class NetGSM
{
    /**
     * Variables.
     *
     * @var array $variables
     */
    private array $variables = [];

    /**
     * Configurations.
     *
     * @var array $config
     */
    public array $config = [];

    /**
     * NetGSM constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Variable overload.
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->variables[$name] = $value;
    }

    /**
     * Variable overload.
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->variables[$name])) {
            return $this->variables[$name];
        }

        return $this->$name;
    }

    /**
     * Function overload.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws Exception
     */
    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, "get")) {
            $variable = Str::of($name)->substr(3)->kebab()->replace("-", "_")->__toString();

            if (isset($this->variables[$variable])) {
                return $this->variables[$variable];
            }
            else {
                throw new Exception;
            }
        }
        else if (Str::startsWith($name, "set")) {
            $variable = Str::of($name)->substr(3)->kebab()->replace("-", "_")->__toString();
            $this->variables[$variable] = $arguments[0];
        }
        else {
            return $this->$name($arguments);
        }
    }
}
