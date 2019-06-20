<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery\DOM;

/**
 * Contains functionality useful for all javascript DOM objects.
 */
abstract class ADomObject
{
    /**
     * Owner where generated code will be assigned.
     * @var \PQuery\PQuery
     */
    protected $owner = null;

    /**
     * Name of DOM object.
     * @var string
     */
    protected $name = "";
    
    public function __construct(string $name, \PQuery\PQuery $owner)
    {
        $this->name = $name;
        $this->owner = $owner;
    }

    /**
     * Magic method that generates the code for non implemented methods.
     *
     * @param string $name
     * @param array $arguments
     * 
     * @return self
     */
    public function __call(string $name, array $arguments): self
    {
        if(!$this->name)
        {
            throw new \Exception("No name specified for the DOM object.");
        }

        switch(count($arguments))
        {
            case 0:
                $this->callMethod($name);
                break;
            case 1:
                $this->callMethod($name, $arguments[0]);
                break;
            case 2:
                $this->callMethod($name, $arguments[0], $arguments[1]);
                break;
            case 3:
                $this->callMethod(
                    $name, $arguments[0], $arguments[1], $arguments[2]
                );
                break;
            case 4:
                $this->callMethod(
                    $name, $arguments[0], $arguments[1], 
                    $arguments[2], $arguments[3]
                );
                break;
            case 5:
                $this->callMethod(
                    $name, $arguments[0], $arguments[1], 
                    $arguments[2], $arguments[3], $arguments[4]
                );
                break;
            default:
                throw new \Exception("Too many arguments.");
        }

        return $this;
    }

    /**
     * Generates code to call DOM object method.
     *
     * @param string $name
     * @param array ...$arguments
     * 
     * @return self
     */
    public function callMethod(string $name, ...$arguments): self
    {
        if(!$this->name)
        {
            throw new \Exception("No name specified for the DOM object.");
        }

        $args = "";
        if(count($arguments) > 0)
        {
            foreach($arguments as $value)
            {
                $this->paramConvert($value);
                
                $args .= $value . ", ";
            }

            $args = rtrim($args, ", ");
        }

        $this->owner->addCode(
            "{$this->name}.$name("
            . $args
            . ");"
        );

        return $this;
    }

    /**
     * Generates code that sets a DOM object property.
     *
     * @param string $name
     * @param string|array|object $value
     * 
     * @return self
     */
    public function assignProperty(string $name, $value): self
    {
        if(!$this->name)
        {
            throw new \Exception("No name specified for the DOM object.");
        }

        $this->paramConvert($value);

        $this->owner->addCode("{$this->name}.$name=$value;");

        return $this;
    }

    /**
     * Converts the paremeter to a valid string or object that can be feed
     * into a javascript function call. If the js: prefix is part if
     * the parameter then the 'js:' string is stripped and no conversion
     * to string is performed becase js: means that this isn't in fact a
     * string but represents a javascript object.
     *
     * @param mixed $param Can be a php array/object, number or string with
     * optional js: prefix.
     * 
     * @return void
     */
    public function paramConvert(&$param): void
    {
        // NOTE: we dont use paramToStr or paramToJSON here reduce the amount
        // of function calls.

        if(is_numeric($param))
        {
            return;
        }
        elseif(is_string($param))
        {
            if(strlen($param) > 3 && substr($param, 0, 3) == "js:")
            {
                $param = substr_replace($param, "", 0, 3);
                return;
            }

            $param = "'"
                . str_replace(
                    array("'", "\n"), 
                    array("\\'", "\\n"), 
                    $param
                ) 
                . "'"
            ;
        }
        elseif(is_array($param) || is_object($param))
        {
            $param = json_encode($param);
        }
        else
        {
            throw new \Exception(
                "Could not properly convert the given parameter"
            );
        }
    }

    /**
     * Converts a parameter to a valid string.
     *
     * @param string $param
     * 
     * @return void
     */
    public function paramToStr(string &$param): void
    {
        $param = "'"
            . str_replace(
                array("'", "\n"), 
                array("\\'", "\\n"), 
                $param
            ) 
            . "'"
        ;
    }

    /**
     * Converts a parameter to a JSON object.
     *
     * @param mixed $param
     * 
     * @return void
     */
    public function paramToJSON(&$param): void
    {
        $param = json_encode($param);
    }
}