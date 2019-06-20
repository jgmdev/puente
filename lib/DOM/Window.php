<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery\DOM;

/**
 * Represets the javascript window object.
 */
class Window extends ADomObject
{
    /**
     * Access to the window console.
     * @var \PQuery\DOM\Console
     */
    private $console;

    /**
     * Access to the window location.
     * @var \PQuery\DOM\Location
     */
    private $location;
    
    /**
     * Constructor
     *
     * @param \PQuery\PQuery $owner
     * @param string $name Default is window but you may override this
     * to get a specific window object eg: myWindow
     */
    public function __construct(\PQuery\PQuery $owner, string $name="window")
    {
        parent::__construct($name, $owner);

        $this->console = new Console($owner);

        $this->location = new Location($owner);
    }

    /**
     * Gives you access to the browser console which provides methods 
     * for logging information to the browser's console
     *
     * @return \PQuery\DOM\Console
     */
    public function console(): Console
    {
        return $this->console;
    }

    /**
     * Gives you access to the location object.
     * 
     * @return \PQuery\DOM\Location
     */
    public function location(): Location
    {
        return $this->location;
    }

    /**
     * Display an alert dialog box.
     *
     * @param string $message
     * 
     * @return \PQuery\DOM\Window
     */
    public function alert(string $message): self
    {
        $this->callMethod("alert", $message);

        return $this;
    }

    /**
     * Generates a confirm dialog, and calls the given server side callback.
     *
     * @param string $message
     * @param callable $callback function(PQuery\PQuery, array{"confirm" => bool})
     * 
     * @return \PQuery\DOM\Window
     */
    public function confirm(string $message, callable $callback): self
    {
        $this->paramConvert($message);

        $this->owner->addCode("var output = confirm($message);");

        $this->owner->addEvent($callback, '{"confirm": output}');
        
        return $this;
    }

    /**
     * Opens a prompt dialog to ask user for input.
     *
     * @param string $message
     * @param callable $callback function(PQuery\PQuery, array{"input" => string})
     * @param string $default_value The default value of the prompt displayed 
     * to the user.
     * 
     * @return \PQuery\DOM\Window
     */
    public function prompt(
        string $message, callable $callback, string $default_value=""
    ): self
    {
        $this->paramConvert($message);
        $this->paramConvert($default_value);

        $this->owner->addCode(
            "var input = prompt($message, $default_value);"
        );

        $this->owner->addEvent($callback, '{"input": input}');
        
        return $this;
    }

    /**
     * Opens a new browser window or tab depending on the user browser settings.
     *
     * @param string $url The url to open, a blank value will open a new tab.
     * @param string $target Can be _blank, _parent, _self or _top.
     * 
     * @return \PQuery\Window Reference to newly created window.
     */
    public function open(string $url, string $target="_blank"): self
    {
        $this->paramConvert($url);
        $this->paramConvert($target);

        $win_name = uniqid("win");

        $this->owner->addCode(
            $win_name."=".$this->name.".open($url, $target);"
        );

        $new_window = new self($this->owner, $win_name);
        
        return $new_window;
    }

    /**
     * Closes the current window.
     *
     * @return \PQuery\DOM\Window
     */
    public function close(): self
    {
        $this->callMethod("close");
        return $this;
    }

    /**
     * Opens the Print Dialog Box, which lets the user select preferred 
     * printing options to print the content of the current window.
     *
     * @return \PQuery\DOM\Window
     */
    public function print(): self
    {
        $this->callMethod("print");
        return $this;
    }

    /**
     * Calls a function after a specified number of milliseconds.
     *
     * @param callable $callback
     * @param int $milliseconds
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\Window
     */
    public function setTimeout(
        callable $callback, int $milliseconds=0, $data="{}"
    ): self
    {
        $this->owner->addEventCallback(
            $this->name.".setTimeout({callback}, $milliseconds);",
            $callback,
            $data
        );
        
        return $this;
    }
}