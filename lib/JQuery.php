<?php
/*
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente;

/**
 * Represents a jQuery instance of a previously declared jQuery object
 * variable.
 */
class JQuery extends DOM\ADomObject
{
    /**
     * Stores generated code if no owner is given.
     * @var string
     */
    protected $code;

    /**
     * Constructor
     *
     * @param string $varname
     * @param ?\Puente\Puente $owner
     */
    public function __construct(string $varname, ?Puente $owner=null)
    {
        parent::__construct($varname, $owner);

        $this->toggleChainable();
    }

    /**
     * Get an individual jquery instance that can be used on function calls.
     *
     * @param string $selector
     *
     * @return JQuery
     */
    public static function jq(string $selector): JQuery
    {
        $util = new self("");
        $util->paramConvert($selector);

        $jquery = new self("jQuery($selector)");

        return $jquery;
    }

    // Dom functions

    /**
     * Set the attribute value for the element.
     *
     * @param string $name
     * @param string $value
     *
     * @return \Puente\JQuery
     */
    public function attr(string $name, string $value=""): self
    {
        if($value)
            $this->callMethod("attr", $name, $value);
        else
            $this->callMethod("attr", $name);

        return $this;
    }

    /**
     * Remove an attribute from each element in the set of matched elements.
     *
     * @param string $name
     *
     * @return \Puente\JQuery
     */
    public function removeAttr(string $name): self
    {
        $this->callMethod("removeAttr", $name);

        return $this;
    }

    /**
     * Set one or more properties for the set of matched elements. In constrast
     * to attr this function allows you to change the internal DOM object
     * properties.
     *
     * @param array|string $properties Eg: ["tagName" => "div"]
     *
     * @return \Puente\JQuery
     */
    public function prop($properties): self
    {
        $this->callMethod("prop", $properties);

        return $this;
    }

    /**
     * Remove a property for the set of matched elements.
     *
     * @param string $name
     *
     * @return \Puente\JQuery
     */
    public function removeProp(string $name): self
    {
        $this->callMethod("removeProp", $name);

        return $this;
    }

    /**
     * Sets the html content of the element.
     *
     * @param string $html
     *
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
     */
    public function remove(string $selector=""): self
    {
        if($selector)
            $this->callMethod("remove", $selector);
        else
            $this->callMethod("remove");

        return $this;
    }

    /**
     * Removes the child elements from the selected element.
     *
     * @return \Puente\JQuery
     */
    public function empty(): JQuery
    {
        $this->callMethod("empty");

        return $this;
    }

    // Traversing methods

    /**
     * Get the parent of each element in the current set of matched elements,
     * optionally filtered by a selector.
     *
     * @param string $selector A string containing a selector expression to
     * match elements against.
     *
     * @return \Puente\JQuery
     */
    public function parent(string $selector=""): self
    {
        if($selector)
            $this->callMethod("parent", $selector);
        else
            $this->callMethod("parent");

        return $this;
    }

    /**
     * Get the descendants of each element in the current set of matched
     * elements, filtered by a selector.
     *
     * @param string $selector A string containing a selector expression to
     * match elements against.
     *
     * @return \Puente\JQuery
     */
    public function find(string $selector): self
    {
        $this->callMethod("find", $selector);

        return $this;
    }

    /**
     * Get the children of each element in the set of matched elements,
     * optionally filtered by a selector.
     *
     * @param string $selector A string containing a selector expression to
     * match elements against.
     *
     * @return \Puente\JQuery
     */
    public function children(string $selector=""): self
    {
        if($selector)
            $this->callMethod("children", $selector);
        else
            $this->callMethod("children");

        return $this;
    }

    /**
     * Get the immediately following sibling of each element in the set
     * of matched elements filtered by an optional selector.
     *
     * @param string $selector A string containing a selector expression to
     * match elements against.
     *
     * @return \Puente\JQuery
     */
    public function next(string $selector=""): self
    {
        if($selector)
            $this->callMethod("next", $selector);
        else
            $this->callMethod("next");

        return $this;
    }

    /**
     * Get the immediately preceding sibling of each element in the set
     * of matched elements filtered by an optional selector.
     *
     * @param string $selector A string containing a selector expression to
     * match elements against.
     *
     * @return \Puente\JQuery
     */
    public function prev(string $selector=""): self
    {
        if($selector)
            $this->callMethod("prev", $selector);
        else
            $this->callMethod("prev");

        return $this;
    }

    // CSS methods

    /**
     * Adds one or more classes to the selected elements.
     *
     * @param string $class Space separeted list of classes.
     *
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
     */
    public function css(array $attributes): self
    {
        $this->callMethod("css", $attributes);

        return $this;
    }

    /**
     * Sets the current coordinates of selected element
     *
     * @param array $coordinates Can contain position values like top, left,
     * bottom and right. Eg: ["top" => 100, "left" => 20]
     *
     * @return \Puente\JQuery
     */
    public function offset(array $coordinates): self
    {
        $this->callMethod("offset", $coordinates);

        return $this;
    }

    /**
     * Set the current vertical position of the scroll bar for each of the
     * set of matched elements.
     *
     * @param string $value
     *
     * @return \Puente\JQuery
     */
    public function scrollTop(string $value): self
    {
        $this->callMethod("scrollTop", $value);

        return $this;
    }

    /**
     * Set the current horizontal position of the scroll bar for each of the
     * set of matched elements.
     *
     * @param string $value
     *
     * @return \Puente\JQuery
     */
    public function scrollLeft(string $value): self
    {
        $this->callMethod("scrollLeft", $value);

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
     * @return \Puente\JQuery
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

    /**
     * Execute all handlers and behaviors attached to the matched elements
     * for the given event type.
     *
     * @param string $event A JavaScript event type such as click or submit.
     * @param string|array|object $param JSON object to feed into
     * each of the invoked events or a php array/object.
     *
     * @return \Puente\JQuery
     */
    public function trigger(string $event, $param=""): self
    {
        if($param != "")
        {
            $param = is_string($param) && substr($param, 0, 3) != "js:" ?
                "js:$param" : $param
            ;

            $this->callMethod("trigger", $event, $param);
        }
        else
        {
            $this->callMethod("trigger", $event);
        }

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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * Bind an event handler to the “select” JavaScript event.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function select(callable $callback, $data="{}"): self
    {
        return $this->on(
            "select",
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
     */
    public function blur(callable $callback, $data="{}"): self
    {
        return $this->on(
            "blur",
            $callback,
            $data
        );
    }

    /**
     * Bind an event handler to the "focusin" event.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function focusin(callable $callback, $data="{}"): self
    {
        return $this->on(
            "focusin",
            $callback,
            $data
        );
    }

    /**
     * Bind an event handler to the "focusout" event.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function focusout(callable $callback, $data="{}"): self
    {
        return $this->on(
            "focusout",
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
     * @return \Puente\JQuery
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
     * A function to execute when the DOM is fully loaded.
     *
     * @param callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function ready(callable $callback, $data="{}"): self
    {
        return $this->on(
            "ready",
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @return \Puente\JQuery
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
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    private function runEffect(
        string $effect, string $speed="", ?callable $callback=null, $data="{}"
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
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function hide(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("hide", $speed, $callback, $data);
    }

    /**
     * Shows the element on the browser.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function show(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("show", $speed, $callback, $data);
    }

    /**
     * Toggles between show/hide.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function toggle(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("toggle", $speed, $callback, $data);
    }

    /**
     * Gradually increases the opacity of the element until it is fully visible.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function fadeIn(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeIn", $speed, $callback, $data);
    }

    /**
     * Gradually decreases the opacity of the element until it is fully hidden.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function fadeOut(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeOut", $speed, $callback, $data);
    }

    /**
     * Toggles between fadeIn and fadeOut.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function fadeToggle(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("fadeToggle", $speed, $callback, $data);
    }

    /**
     * Lets you gradually set the opacity to a specific level.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param float $to Level of opacity
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function fadeTo(
        string $speed, float $to, ?callable $callback=null, $data="{}"
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
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function slideDown(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideDown", $speed, $callback, $data);
    }

    /**
     * Gradually unexpands an element until it is hidden.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function slideUp(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideUp", $speed, $callback, $data);
    }

    /**
     * Toggles between slideDown/slideUp.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function slideToggle(
        string $speed="", ?callable $callback=null, $data="{}"
    ): self
    {
        return $this->runEffect("slideToggle", $speed, $callback, $data);
    }

    /**
     * Gradually changes the css attributes of the element to make the
     * desired animation effect.
     *
     * @param string $speed Can be: fast, slow or milliseconds amount.
     * @param ?callable $callback
     * @param string|array|object $data The data you want on your callback
     * as a json string or php array|object, eg: '{width: window.innerWidth}'
     *
     * @return \Puente\JQuery
     */
    public function animate(
        array $css, string $speed="", ?callable $callback=null, $data="{}"
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
     * Stop the currently-running animation, remove all queued animations,
     * and complete all animations for the matched elements.
     *
     * @param string $queue The name of the queue in which to stop animations.
     * default is 'fx'.
     *
     * @return \Puente\JQuery
     */
    public function finish(string $queue=""): self
    {
        if($queue)
            $this->callMethod("finish", $queue);
        else
            $this->callMethod("finish");

        return $this;
    }

    /**
     * Stops the animation of current element.
     *
     * @return \Puente\JQuery
     */
    public function stop(): self
    {
        $this->callMethod("stop");

        return $this;
    }
}
