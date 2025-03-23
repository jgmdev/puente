<?php
/*
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente\DOM;

/**
 * Represets the javascript window object.
 */
class Window extends ADomObject
{
    /**
     * Access to the window console.
     * @var \Puente\DOM\Console
     */
    private $console;

    /**
     * Access to the window location.
     * @var \Puente\DOM\Location
     */
    private $location;

    /**
     * Constructor
     *
     * @param \Puente\Puente $owner
     * @param string $name Default is window but you may override this
     * to get a specific window object eg: myWindow
     */
    public function __construct(\Puente\Puente $owner, string $name="window")
    {
        parent::__construct($name, $owner);

        $this->console = new Console($owner);

        $this->location = new Location($owner);
    }

    /**
     * Gives you access to the browser console which provides methods
     * for logging information to the browser's console
     *
     * @return \Puente\DOM\Console
     */
    public function console(): Console
    {
        return $this->console;
    }

    /**
     * Gives you access to the location object.
     *
     * @return \Puente\DOM\Location
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
     * @return \Puente\DOM\Window
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
     * @param callable $callback function(Puente\Puente, array{"confirm" => bool})
     *
     * @return \Puente\DOM\Window
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
     * @param callable $callback function(Puente\Puente, array{"input" => string})
     * @param string $default_value The default value of the prompt displayed
     * to the user.
     *
     * @return \Puente\DOM\Window
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
     * Stores the window object in the puente storage for later manipulation.
     *
     * @param string $url The url to open, a blank value will open a new tab.
     * @param string $target Can be _blank, _parent, _self or _top.
     * @param string $varname An explicit name for the variable that will store
     * the new opened window.
     *
     * @return \Puente\DOM\Window Reference to newly created window.
     */
    public function open(
        string $url, string $target="_blank", string $varname=""
    ): self
    {
        $this->paramConvert($url);
        $this->paramConvert($target);

        $varname = $varname == "" ? uniqid("win") : $varname;

        $this->owner->addCode(
            $varname."=".$this->name.".open($url, $target);"
        );

        $this->owner->puenteStorage()->insertVar($varname);

        $new_window = new self($this->owner, $varname);

        return $new_window;
    }

    /**
     * Closes the current window.
     *
     * @return \Puente\DOM\Window
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
     * @return \Puente\DOM\Window
     */
    public function print(): self
    {
        $this->callMethod("print");
        return $this;
    }

    /**
     * Remove focus from the current window.
     *
     * @return \Puente\DOM\Window
     */
    public function blur(): self
    {
        $this->callMethod("print");
        return $this;
    }

    /**
     * Sets focus to the current window.
     *
     * @return \Puente\DOM\Window
     */
    public function focus(): self
    {
        $this->callMethod("print");
        return $this;
    }

    /**
     * Calls a function after a specified number of milliseconds. This function
     * will add a 'timeout' element to the $data object sent to the callback
     * that contains the variable name which holds the timer id.
     *
     * @param callable $callback
     * @param int $milliseconds
     * @param string $varname An explicit name for the variable that will store
     * the timer id, if empty it will generate one for you.
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\DOM\Window
     */
    public function setTimeout(
        callable $callback, int $milliseconds=0, string $varname="", $data="{}"
    ): self
    {
        $varname = $varname == "" ? uniqid("timeout") : $varname;

        $this->appendData($data, ["timeout" => $varname]);

        $this->owner->addEventCallback(
            "var $varname = {$this->name}.setTimeout("
                . "{callback}, $milliseconds"
                . ");",
            $callback,
            $data
        );

        $this->owner->puenteStorage()->insertVar($varname);

        return $this;
    }

    /**
     * Calls a function at specified intervals in milliseconds. This function
     * will add an 'interval' element to the $data object sent to the callback
     * that contains the variable name whichs holds timer id, this way
     * you can use clearInterval from within the callback in case you want
     * to stop the interval.
     *
     * @param callable $callback
     * @param int $milliseconds
     * @param string $varname An explicit name for the variable that will store
     * the timer id, if empty it will generate one for you.
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\DOM\Window
     */
    public function setInterval(
        callable $callback, int $milliseconds=0, string $varname="", $data="{}"
    ): self
    {
        $varname = $varname == "" ? uniqid("interval") : $varname;

        $this->appendData($data, ["interval" => $varname]);

        $this->owner->addEventCallback(
            "var $varname = {$this->name}.setInterval("
                . "{callback}, $milliseconds"
                . ");",
            $callback,
            $data
        );

        $this->owner->puenteStorage()->insertVar($varname);

        return $this;
    }

    /**
     * Stops a timer started with setTimeout().
     *
     * @param string $varname
     *
     * @return \Puente\DOM\Window
     */
    public function clearTimeout(string $varname): self
    {
        $this->owner->addCode(
            "clearTimeout("
                . $this->owner->puenteStorage()->getVarInstance($varname)
                . ");"
        );

        $this->owner->puenteStorage()->removeVar($varname);

        return $this;
    }

    /**
     * Stops a timer started with setInterval().
     *
     * @param string $varname
     *
     * @return \Puente\DOM\Window
     */
    public function clearInterval(string $varname): self
    {
        $this->owner->addCode(
            "clearInterval("
                . $this->owner->puenteStorage()->getVarInstance($varname)
                . ");"
        );

        $this->owner->puenteStorage()->removeVar($varname);

        return $this;
    }

    /**
     * Resizes a window to the specified width and height.
     *
     * @param integer $width
     * @param integer $height
     *
     * @return \Puente\DOM\Window
     */
    public function resizeTo(int $width, int $height): self
    {
        $this->callMethod("resizeTo", $width, $height);

        return $this;
    }

    /**
     * Moves a window's left and top edge to the specified coordinates.
     *
     * @param integer $width
     * @param integer $height
     *
     * @return \Puente\DOM\Window
     */
    public function moveTo(int $width, int $height): self
    {
        $this->callMethod("moveTo", $width, $height);

        return $this;
    }
}