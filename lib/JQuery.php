<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery;

/**
 * Represents a jQuery instance of a previously declared jQuery object
 * variable.
 */
class JQuery extends DOM\ADomObject
{
    /**
     * Constructor
     *
     * @param string $varname
     * @param \PQuery\PQuery $owner
     */
    public function __construct(string $varname, PQuery $owner)
    {
        parent::__construct($varname, $owner);
    }

    // Dom functions

    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $value
     * 
     * @return \PQuery\JQuery
     */
    public function attr(string $name, string $value): self
    {
        $this->callMethod("attr", $name, $value);
        
        return $this;
    }

    /**
     * Sets the html content of the element.
     *
     * @param string $html
     * 
     * @return \PQuery\JQuery
     */
    public function html(string $html): self
    {
        $this->callMethod("html", $html);
        
        return $this;
    }

    /**
     * Sets the inner text of the element.
     *
     * @param string $text
     * 
     * @return \PQuery\JQuery
     */
    public function text(string $text): self
    {
        $this->callMethod("text", $text);
        
        return $this;
    }

    /**
     * Sets the value of a valid form element.
     *
     * @param string $value
     * 
     * @return \PQuery\JQuery
     */
    public function val(string $value): self
    {
        $this->callMethod("val", $value);
        
        return $this;
    }

    /**
     * Inserts content at the end of the selected elements.
     *
     * @param string $element
     * 
     * @return \PQuery\JQuery
     */
    public function append(string $element): self
    {
        $this->callMethod("append", $element);

        return $this;
    }

    /**
     * Inserts content at the beginning of the selected elements.
     *
     * @param string $element
     * 
     * @return \PQuery\JQuery
     */
    public function prepend(string $element): self
    {
        $this->callMethod("prepend", $element);

        return $this;
    }

    /**
     * Inserts content after the selected elements.
     *
     * @param string $element
     * 
     * @return \PQuery\JQuery
     */
    public function after(string $element): self
    {
        $this->callMethod("after", $element);

        return $this;
    }

    /**
     * Inserts content before the selected elements.
     *
     * @param string $element
     * 
     * @return \PQuery\JQuery
     */
    public function before(string $element): self
    {
        $this->callMethod("before", $element);

        return $this;
    }

    /**
     * Removes the selected element (and its child elements).
     *
     * @param string $selector Specific child element to remove.
     * 
     * @return \PQuery\JQuery
     */
    public function remove(string $selector=""): self
    {
        $this->callMethod("remove", $selector);

        return $this;
    }

    /**
     * Removes the child elements from the selected element.
     *
     * @return \PQuery\JQuery
     */
    public function empty(): JQuery
    {
        $this->callMethod("empty");
        
        return $this;
    }

    // CSS methods

    /**
     * Adds one or more classes to the selected elements.
     *
     * @param string $class Space separeted list of classes.
     * 
     * @return \PQuery\JQuery
     */
    public function addClass(string $class): self
    {
        $this->callMethod("addClass", $class);
        
        return $this;
    }

    /**
     * Removes one or more classes from the selected elements.
     *
     * @param string $class Space separeted list of classes.
     * 
     * @return \PQuery\JQuery
     */
    public function removeClass(string $class): self
    {
        $this->callMethod("removeClass", $class);
        
        return $this;
    }

    /**
     * Toggles between adding/removing classes from the selected elements.
     *
     * @param string $class Space separeted list of classes.
     * 
     * @return \PQuery\JQuery
     */
    public function toggleClass(string $class): self
    {
        $this->callMethod("toggleClass", $class);
        
        return $this;
    }

    /**
     * Sets one or more style properties for the selected elements.
     *
     * @param array $attributes
     * 
     * @return \PQuery\JQuery
     */
    public function css(array $attributes): self
    {
        $this->callMethod("css", $attributes);
        
        return $this;
    }

    /**
     * Listen for events of the type $event.
     *
     * @param string $event Type of event. Values can be: click, dblclick,
     * change, keyup, keydown, etc...
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function on(string $event, callable $callback, $data="{}"): self
    {
        $this->owner->addElementEvent(
            $this->name, 
            $event,
            $callback,
            $data
        );

        return $this;
    }

    // Mouse Events

    /**
     * Event called when the element is clicked.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function click(callable $callback, $data="{}"): self
    {
        return $this->on(
            "click",
            $callback,
            $data
        );
    }

    /**
     * Event called when the element is double clicked.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function dblclick(callable $callback, $data="{}"): self
    {
        return $this->on(
            "dblclick",
            $callback,
            $data
        );
    }

    /**
     * Event called when the mouse is on top of the element.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function mouseenter(callable $callback, $data="{}"): self
    {
        return $this->on(
            "mouseenter",
            $callback,
            $data
        );
    }

    /**
     * Event called when the mouse leaves the element.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function mouseleave(callable $callback, $data="{}"): self
    {
        return $this->on(
            "mouseleave",
            $callback,
            $data
        );
    }

    // Keypress Events

    /**
     * Event called when the element receives a key press that is not a key
     * modifier like the ALT, SHIFT, CTRL, ESC, etc...
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function keypress(callable $callback, $data="{}"): self
    {
        return $this->on(
            "keypress",
            $callback,
            $data
        );
    }

    /**
     * Event called when the element receives any key press.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function keydown(callable $callback, $data="{}"): self
    {
        return $this->on(
            "keypress",
            $callback,
            $data
        );
    }

    /**
     * Event called when a key is released.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function keyup(callable $callback, $data="{}"): self
    {
        return $this->on(
            "keyup",
            $callback,
            $data
        );
    }

    // Form Events

    /**
     * Event called when the form is submitted.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function submit(callable $callback, $data="{}"): self
    {
        return $this->on(
            "submit",
            $callback,
            $data
        );
    }

    /**
     * Event called when the form element value changes.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function change(callable $callback, $data="{}"): self
    {
        return $this->on(
            "change",
            $callback,
            $data
        );
    }

    /**
     * Event called when the form element receives focus.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function focus(callable $callback, $data="{}"): self
    {
        return $this->on(
            "focus",
            $callback,
            $data
        );
    }

    /**
     * Event called when the form element looses focus.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function blur(callable $callback, $data="{}"): self
    {
        return $this->on(
            "blur",
            $callback,
            $data
        );
    }

    // Window/Document Events

    /**
     * Event called when the element finishes loading.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function load(callable $callback, $data="{}"): self
    {
        return $this->on(
            "load",
            $callback,
            $data
        );
    }

    /**
     * Event called when the element is resized.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function resize(callable $callback, $data="{}"): self
    {
        return $this->on(
            "resize",
            $callback,
            $data
        );
    }

    /**
     * Event called when the element is scrolled.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function scroll(callable $callback, $data="{}"): self
    {
        return $this->on(
            "scroll",
            $callback,
            $data
        );
    }

    /**
     * Event called when the element, for example a window is going to be closed.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function unload(callable $callback, $data="{}"): self
    {
        return $this->on(
            "unload",
            $callback,
            $data
        );
    }

    // Effects

    /**
     * Generic code to run a jQuery effects function.
     *
     * @param string $effect Name of the jquery effect function.
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    private function runEffect(
        string $effect, string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        $speed = $speed === "" ? "fast" : $speed;

        if($callback == null)
        {
            $this->callMethod($effect, $speed);
        }
        else
        {
            $this->paramConvert($speed);
            $this->owner->addEventCallback(
                $this->name.".$effect($speed, {callback});",
                $callback,
                $data
            );
        }
        
        return $this;
    }

    /**
     * Hides the element on the browser.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function hide(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("hide", $speed, $callback, $data);
    }

    /**
     * Shows the element on the browser.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function show(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("show", $speed, $callback, $data);
    }

    /**
     * Toggles between show/hide.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function toggle(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("toggle", $speed, $callback, $data);
    }

    /**
     * Gradually increases the opacity of the element until it is fully visible.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function fadeIn(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeIn", $speed, $callback, $data);
    }

    /**
     * Gradually decreases the opacity of the element until it is fully hidden.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function fadeOut(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeOut", $speed, $callback, $data);
    }

    /**
     * Toggles between fadeIn and fadeOut.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function fadeToggle(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeToggle", $speed, $callback, $data);
    }

    /**
     * Lets you gradually set the opacity to a specific level.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param float $to Level of opacity
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function fadeTo(
        string $speed, float $to, callable $callback=null, $data="{}"
    ): self
    {
        $speed = $speed === "" ? "fast" : $speed;

        if($callback == null)
        {
            $this->callMethod("fadeTo", $speed, $to);
        }
        else
        {
            $this->paramConvert($speed);
            $this->paramConvert($to);

            $this->owner->addEventCallback(
                $this->name.".fadeTo($speed, $to, {callback});",
                $callback,
                $data
            );
        }
        
        return $this;
    }

    /**
     * Gradually expands an element until it is visible.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function slideDown(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideDown", $speed, $callback, $data);
    }

    /**
     * Gradually unexpands an element until it is hidden.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function slideUp(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideUp", $speed, $callback, $data);
    }

    /**
     * Toggles between slideDown/slideUp.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function slideToggle(
        string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideToggle", $speed, $callback, $data);
    }

    /**
     * Gradually changes the css attributes of the element to make the
     * desired animation effect.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     * 
     * @return \PQuery\JQuery
     */
    public function animate(
        array $css, string $speed="", callable $callback=null, $data="{}"
    ): self
    {
        $speed = $speed === "" ? "fast" : $speed;

        if($callback == null)
        {
            $this->callMethod("animate", $css, $speed);
        }
        else
        {
            $this->paramConvert($css);
            $this->paramConvert($speed);

            $this->owner->addEventCallback(
                $this->name.".animate($css, $speed, {callback});",
                $callback,
                $data
            );
        }
        
        return $this;
    }

    /**
     * Stops the animation of current element.
     *
     * @return \PQuery\JQuery
     */
    public function stop(): self
    {
        $this->callMethod("stop");
        
        return $this;
    }
}