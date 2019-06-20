<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Representation of the console DOM object which is useful to pring
 * debugging messages into the browser console.
 */
class Console extends ADomObject
{   
    /**
     * Constructor.
     *
     * @param \Puente\Puente $owner
     * @param string $name Default is console but you may override this
     * to get the console object of a specific element eg: myWindow.console
     */
    public function __construct(\Puente\Puente $owner, string $name="console")
    {
        parent::__construct($name, $owner);
    }

    /**
     * Writes an assert message to console.
     *
     * @param string|array|object $element A string representing a javascript 
     * object or php array of associative elements, for example:
     * [["name" => "john", age => 10], ["name" => "Smith", age => 4]].
     * @param string $message The message to display with the element.
     * 
     * @return \Puente\DOM\Console
     */
    public function assert(
        $element, string $message=""
    ): self
    {
        if(is_string($element))
        {
            $element = "js:$element";
        }

        if($message)
        {
            $this->callMethod("assert", $element, $message);
        }
        else
        {
            $this->callMethod("assert", $element);
        }

        return $this;
    }

    /**
     * Writes an error message to the console.
     * 
     * @param string $message The message to write.
     *
     * @return \Puente\DOM\Console
     */
    public function error(string $message): self
    {
        $this->callMethod("error", $message);

        return $this;
    }

    /**
     * Writes a message to the console
     *
     * @param string $message The message or object to write.
     * 
     * @return \Puente\DOM\Console
     */
    public function info(string $message): self
    {
        $this->callMethod("info", $message);

        return $this;
    }

    /**
     * Writes a message to the console
     *
     * @param string $message The message or object to write.
     * 
     * @return \Puente\DOM\Console
     */
    public function log(string $message): self
    {
        $this->callMethod("log", $message);

        return $this;
    }

    /**
     * Writes a warning message to the console.
     *
     * @param string $message The message or object to write.
     * 
     * @return \Puente\DOM\Console
     */
    public function warn(string $message): self
    {
        $this->callMethod("warn", $message);

        return $this;
    }

    /**
     * Clears the debugging console. and writes a message in the console: 
     * "Console was cleared".
     *
     * @return \Puente\DOM\Console
     */
    public function clear(): self
    {
        $this->callMethod("clear");

        return $this;
    }

    /**
     * Writes to the console the number of times that particular console.count() 
     * is called. You can add a label that will be included in the console view.
     * 
     * @param string $label If set the method counts the number of times 
     * console.count() has been called with this label.
     *
     * @return \Puente\DOM\Console
     */
    public function count(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("count", $label);
        }
        else
        {
            $this->callMethod("count");   
        }

        return $this;
    }

    /**
     * Indicates the start of a message group. All messages will from now on 
     * be written inside this group.
     *
     * @param string $label If set the messages are grouped by the given label.
     * 
     * @return \Puente\DOM\Console
     */
    public function group(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("group", $label);
        }
        else
        {
            $this->callMethod("group");   
        }

        return $this;
    }

    /**
     * Same as group but the user will need to uncollapse messages to read them.
     *
     * @param string $label If set the messages are grouped by the given label.
     * 
     * @return \Puente\DOM\Console
     */
    public function groupCollapsed(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("groupCollapsed", $label);
        }
        else
        {
            $this->callMethod("groupCollapsed");   
        }

        return $this;
    }

    /**
     * Ends a previously started messages group.
     *
     * @return \Puente\DOM\Console
     */
    public function groupEnd(): self
    {
        $this->callMethod("groupEnd");

        return $this;
    }

    /**
     * Writes a table to the console
     *
     * @param string|array $element A string representing a javascript object 
     * or php array of associative elements, for example:
     * [["name" => "john", age => 10], ["name" => "Smith", age => 4]].
     * @param array $columnNames Array of the columns to display, for example:
     * ["age"] which will display only the age column.
     * 
     * @return \Puente\DOM\Console
     */
    public function table(
        $element, array $columnNames=[]
    ): self
    {
        if(is_string($element))
        {
            $element = "js:$element";
        }

        if(count($columnNames) > 0)
        {
            $this->callMethod("table", $element, $columnNames);
        }
        else
        {
            $this->callMethod("table", $element);
        }

        return $this;
    }

    /**
     * Starts a timer that counts the amount of time since started.
     *
     * @param string $label If set the timer is identified as label.
     * 
     * @return \Puente\DOM\Console
     */
    public function time(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("time", $label);
        }
        else
        {
            $this->callMethod("time");   
        }

        return $this;
    }

    /**
     * Ends a timer and write the total amount of time on the console.
     *
     * @param string $label If set the timer is identified as label.
     * 
     * @return \Puente\DOM\Console
     */
    public function timeEnd(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("timeEnd", $label);
        }
        else
        {
            $this->callMethod("timeEnd");   
        }

        return $this;
    }

    /**
     * Displays a trace that show how the code ended before calling.
     * 
     * @param string $label If set the trace is identified as the given label.
     *
     * @return \Puente\DOM\Console
     */
    public function trace(string $label=""): self
    {
        if($label)
        {
            $this->callMethod("trace", $label);
        }
        else
        {
            $this->callMethod("trace");   
        }

        return $this;
    }
}