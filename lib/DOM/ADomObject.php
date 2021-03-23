<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Contains functionality useful for all javascript DOM objects.
 */
abstract class ADomObject
{
    /**
     * Name of DOM object.
     * @var string
     */
    protected $name = "";

    /**
     * Owner where generated code will be assigned.
     * @var ?\Puente\Puente
     */
    protected $owner = null;

    /**
     * Stores generated code if no owner is given.
     * @var string
     */
    protected $code;

    /**
     * Stores the jquery identifier instance.
     * @var string
     */
    protected $identifier;

    public function __construct(string $name, ?\Puente\Puente $owner=null)
    {
        $this->name = $name;
        $this->owner = $owner;
        $this->code = "";
        $this->identifier = "";
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

        if($this->owner)
        {
            if(!$this->identifier)
            {
                $this->owner->addCode(
                    "{$this->name}.$name("
                    . $args
                    . ");"
                );
            }
            else
            {
                $current_code = rtrim(
                    $this->owner->getCode($this->identifier),
                    ";"
                );

                if($current_code)
                {
                    $current_code .= ".$name("
                        . $args
                        . ");"
                    ;
                }
                else
                {
                    $current_code .= "{$this->name}.$name("
                        . $args
                        . ");"
                    ;
                }

                $this->owner->addCode($current_code, $this->identifier);
            }
        }
        else
        {
            if($this->code)
            {
                $this->code .= ".$name("
                    . $args
                    . ")"
                ;
            }
            else
            {
                $this->code .= "{$this->name}.$name("
                    . $args
                    . ")"
                ;
            }
        }

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

        if($this->owner)
        {
            $this->owner->addCode("{$this->name}.$name=$value;");
        }
        else
        {
            if($this->code)
            {
                $this->code .= "; {$this->name}.$name=$value";
            }
            else
            {
                $this->code .= "{$this->name}.$name=$value";
            }
        }

        return $this;
    }

    /**
     * Get generated code if object doesn't have an owner.
     *
     * @return string
     */
    public function getCode(): string
    {
        return "js:".$this->code;
    }

    /**
     * Get generated code if object doesn't have an owner including the ; at
     * the end.
     *
     * @return string
     */
    public function getCodeEnded(): string
    {
        return "js:".$this->code . ";";
    }

    /**
     * Appends data fields to a data object that will be sent to a callback.
     *
     * @param string|array|object $data
     * @param array $new_data
     *
     * @return self
     */
    public function appendData(&$data, array $new_data): self
    {
        if(count($new_data) <= 0)
        {
            return $this;
        }

        if(is_string($data))
        {
            $data = trim($data);

            if(substr($data, -1) == "}")
            {
                $data = substr_replace($data, "", -1);

                foreach($new_data as $name => $value)
                {
                    $this->paramConvert($value);
                    $data .= ", $name: $value";
                }

                if(substr($data, 0, 2) == "{,")
                {
                    $data = substr_replace($data, "{", 0, 2);
                }

                $data .= "}";
            }
            else
            {
                throw new \Exception("Invalid JSON Object.");
            }
        }
        elseif(is_array($data))
        {
            foreach($new_data as $name => $value)
            {
                $this->paramConvert($value);
                $data[$name] = $value;
            }
        }
        elseif(is_object($data))
        {
            foreach($new_data as $name => $value)
            {
                $this->paramConvert($value);
                $data->$name = $value;
            }
        }

        return $this;
    }

    /**
     * Returns the name of the element we are working on, this can be
     * an object or variable name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
                    ["'", "\n"],
                    ["\\'", "\\n"],
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
                ["'", "\n"],
                ["\\'", "\\n"],
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

    /**
     * Generate call code chained, same jquery kind of syntax.
     * Eg: $("selector").call1().call2().call3();
     *
     * @return bool True if chainable turned on or false if not.
     */
    public function toggleChainable(): bool
    {
        if(!$this->identifier)
        {
            $this->identifier = $this->generateIdentifier(10);
            return true;
        }

        $this->identifier = "";

        return false;
    }

    /**
     * Generate unique identifier string.
     *
     * @return string
     */
    public function generateIdentifier($len): string
    {
        $text = "";

        while(strlen($text) < $len)
            $text .= str_replace(
                array("\$", ".", "/"),
                "",
                crypt(
                    uniqid((string)rand($len, intval($len*rand())), true),
                    uniqid("", true)
                )
            );

        if(strlen($text) > $len)
        {
            $text = substr($text, 0, $len);
        }

        return $text;
    }
}
