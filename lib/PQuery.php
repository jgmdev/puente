<?php
/**
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/pquery Source code.
 */

namespace PQuery;

/**
 * Serves as a proxy between PHP and jQuery for easier communication between
 * both. Also is in charge of generating the javascript code and listening
 * for events on the client browser, processing them and sending more javascript 
 * code to the client browser.
 */
class PQuery
{
    private $code = [];
    private $code_buffer = [];
    private $code_buffering = false;
    private $code_buffer_level = 1;
    private $code_buffer_next_level = 1;
    private $events = [];
    private $current_event = 1;
    private $elements = [];
    private $current_element = 1;

    /**
     * Represents the DOM window element.
     * @var \PQuery\DOM\Window
     */
    private $window;

    /**
     * Represents the DOM localStorage element.
     * @var \PQuery\DOM\LocalStorage
     */
    private $local_storage;

    /**
     * Represents the DOM sessionStorage element.
     * @var \PQuery\DOM\SessionStorage
     */
    private $session_storage;

    public function __construct()
    {
        $this->window = new DOM\Window($this);

        $this->local_storage = new DOM\LocalStorage($this);

        $this->session_storage = new DOM\SessionStorage($this);
    }

    private function enableBuffer(): void
    {
        $level = $this->code_buffer_next_level;
        $this->code_buffer_level = $level;

        $this->code_buffer[$level] = [];
        $this->code_buffering = true;

        $this->code_buffer_next_level++;
    }

    private function disableBuffer(): void
    {
        $level = $this->code_buffer_level;
        unset($this->code_buffer[$level]);

        if($this->code_buffer_level > 1)
        {
            $this->code_buffer_level--;
        }

        $this->code_buffer_next_level--;

        if($this->code_buffer_next_level == 1)
        {
            $this->code_buffering = false;
        }
    }

    private function clearBuffer(): void
    {
        if($this->code_buffering)
        {
            $level = $this->code_buffer_level;
            $this->code_buffer[$level] = [];
        }
    }

    private function getParents($id, $data="{}"): array
    {
        if($this->code_buffering)
        {
            return array(
                "decl" => "\nparents.push(parent_id);"
                    . "parents_data[parent_id] = parent_data;"
                    . "parent_id=$id;"
                    . "parent_data=$data;",
                "call" => "parents: parents, parents_data: parents_data,"
            );
        }
        else
        {
            return array(
                "decl" => "\nvar parents=[];"
                    . "var parents_data=[];"
                    . "var parent_id=$id;"
                    . "var parent_data=$data;",
                "call" => ""
            );
        }

        return array();
    }

    /**
     * Check if a selector has the js: prefix and treats it like an object,
     * otherwise it converts it into a string that can be feed to a javascript
     * function call.
     *
     * @param string $selector
     * 
     * @return string
     */
    private function parseSelector(string $selector): string
    {
        if(strlen($selector) > 3 && substr($selector, 0, 3) == "js:")
        {
            $selector = substr_replace($selector, "", 0, 3);
            return $selector;
        }

        return $selector = "'"
            . str_replace(
                array("'", "\n"), 
                array("\\'", "\\n"), 
                $selector
            ) 
            . "'"
        ;
    }

    /**
     * Access a jQuery instance for the given selector.
     *
     * @param string $selector A valid jQuery selector string or dom object
     * using the js: prefix, eg: "js:window", "js:document", etc...
     * 
     * @return JQuery
     */
    public function jq(string $selector): JQuery
    {
        $selector_parsed = $this->parseSelector($selector);

        if(!isset($this->elements[$selector]))
        {
            $varname = "ele".$this->current_element;

            $jquery = new JQuery(
                $varname, $this
            );

            $this->code[] = "var $varname = jq($selector_parsed);";
            
            $this->elements[$selector] = array(
                "var" => $varname,
                "object" => $jquery
            );

            $this->current_element++;

            return $jquery;
        }
        
        return $this->elements[$selector]["object"];
    }

    /**
     * Add hand crafted javascript code.
     *
     * @param string $code
     * 
     * @return \PQuery\PQuery
     */
    public function addCode(string $code): self
    {
        if(!$this->code_buffering)
        {
            $this->code[] = $code;
        }
        else
        {
            $this->code_buffer[$this->code_buffer_level][] = $code;
        }

        return $this;
    }

    /**
     * Gives you access to the window DOM object.
     *
     * @return \PQuery\DOM\Window
     */
    public function window(): DOM\Window
    {
        return $this->window;
    }

    /**
     * Gives you access to the localStorage object.
     *
     * @return \PQuery\DOM\LocalStorage
     */
    public function localStorage(): DOM\LocalStorage
    {
        return $this->local_storage;
    }

    /**
     * Gives you access to the sessionStorage object.
     *
     * @return \PQuery\DOM\SessionStorage
     */
    public function sessionStorage(): DOM\SessionStorage
    {
        return $this->session_storage;
    }

    /**
     * Generates an ajax callback back to the server.
     *
     * @param string $type
     * @param callable $callback
     * @param string|array|object $data A valid json string or php array/object.
     * 
     * @return void
     */
    public function addEvent(
        callable $callback, $data="{}"
    ): self
    {
        $id = $this->current_event;
        $this->current_event++;

        $this->events[$id] = $callback;

        if(is_array($data) || is_object($data))
        {
            $data = json_encode($data);
        }

        $parents = $this->getParents($id, $data);

        $code = $parents["decl"]
            . "jq.ajax("
            . "{"
            . "url: window.location.pathname, "
            . "dataType: 'json', "
            . "data: {pquery: 1, {$parents['call']} id: '$id', data: $data}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . "}"
            . "}).fail(function(data){"
            . "alert('Error Occurred');"
            . "});"
        ;

        $this->addCode($code);

        return $this;
    }

    /**
     * Generates an ajax callback that can be used for javascript functions
     * that require a callback.
     *
     * @param string $code JS Code with placeholder for callback function, for
     * example: $("element").hide(10, {callback}) where {callback} gets replaced
     * with the actual generated callback code.
     * @param callable $callback
     * @param string|array|object $data A valid json string or php array/pbject. 
     * For example: "{width: window.innerWidth}"
     * 
     * @return \PQuery\PQuery
     */
    public function addEventCallback(
        string $code, callable $callback, $data="{}"
    ): self
    {
        $id = $this->current_event;
        $this->current_event++;

        $this->events[$id] = $callback;

        if(is_array($data) || is_object($data))
        {
            $data = json_encode($data);
        }

        $parents = $this->getParents($id, $data);

        $callback_code = "function(event){"
            . $parents["decl"]
            . "jq.ajax("
            . "{"
            . "url: window.location.pathname, "
            . "dataType: 'json', "
            . "data: {pquery: 1, {$parents['call']} id: '$id', data: $data}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . "}"
            . "}).fail(function(data){"
            . "alert('Error Occurred');"
            . "});}"
        ;

        $code = str_replace("{callback}", $callback_code, $code);

        $this->addCode($code);

        return $this;
    }

    /**
     * Registers an ajax callback for a given element event.
     *
     * @param string $varname Variable name of the element.
     * @param string $type Event type, eg: click, dblclick, etc...
     * @param callable $callback
     * @param string|array|object $data A valid json string or php array/pbject. 
     * For example: "{width: window.innerWidth}"
     * 
     * @return \PQuery\PQuery
     */
    public function addElementEvent(
        string $varname, string $type, callable $callback, $data="{}"
    ): self
    {
        $id = $this->current_event;
        $this->current_event++;

        $this->events[$id] = $callback;

        if(is_array($data) || is_object($data))
        {
            $data = json_encode($data);
        }

        $parents = $this->getParents($id, $data);

        $code = "$varname.on('$type', function(event){"
            . $parents["decl"]
            . "jq.ajax("
            . "{"
            . "url: window.location.pathname, "
            . "dataType: 'json', "
            . "data: {pquery: 1, {$parents['call']} id: '$id', element: '$varname', data: $data}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . "}"
            . "}).fail(function(data){"
            . "alert('Error Occurred');"
            . "});"
            . "});"
        ;

        $this->addCode($code);

        return $this;
    }

    /**
     * Process events made from the browser.
     *
     * @return void
     */
    public function listenRequest(): void
    {
        if(isset($_REQUEST["pquery"]))
        {
            header('Content-Type: application/json; charset=utf-8', true);
            
            $data = array();
            $pquery = $this;

            if(isset($_REQUEST["id"]))
            {
                $id = $_REQUEST["id"];

                if(isset($this->events[$id]))
                {
                    $callback = $this->events[$id];

                    $pquery->enableBuffer();

                    $callback(
                        $pquery, 
                        $_REQUEST["data"]
                    );

                    $data["code"] = $pquery->getPlainCode();

                    $pquery->disableBuffer();
                }
                else
                {
                    if(isset($_REQUEST["parents"]))
                    {
                        $pquery->enableBuffer();

                        foreach($_REQUEST["parents"] as $parent)
                        {
                            $data = array();

                            if(
                                isset($_REQUEST["parents_data"])
                                &&
                                isset($_REQUEST["parents_data"][$parent])
                            )
                            {
                                $data = $_REQUEST["parents_data"][$parent];
                            }

                            $this->events[$parent](
                                $pquery, $data
                            );
                        }

                        $pquery->clearBuffer();

                        if(isset($this->events[$id]))
                        {
                            $callback = $this->events[$id];

                            $callback(
                                $pquery, 
                                $_REQUEST["data"]
                            );

                            $data["code"] = $pquery->getPlainCode();
                        }
                        else
                        {
                            $data["error"] = "No child id registered.";
                        }

                        $pquery->disableBuffer();
                    }
                    else
                    {
                        $data["error"] = "No id registered.";
                    }
                }
            }
            else
            {
                $data["error"] = "No callback id given.";
            }

            print json_encode($data);

            exit;
        }
    }

    /**
     * Gets the generated code.
     *
     * @return string
     */
    public function getPlainCode(): string
    {
        $code = "";
        if(!$this->code_buffering)
        {
            $code .= "(function(jq) {\n"
                . "  " //indentation
                . implode("\n  ", $this->code)
                . "\n"
                . "})(jQuery);\n"
            ;
        }
        else
        {
            $code .= "(function(jq) {\n"
                . "  " //indentation
                . implode("\n  ", $this->code_buffer[$this->code_buffer_level])
                . "\n"
                . "})(jQuery);\n"
            ;
        }

        return $code;
    }

    /**
     * Gets the generated code ready to insert into html document.
     *
     * @return string
     */
    public function getExecuteCode(): string
    {
        $code = "<script>\n"
            . "jQuery(document).ready(function(){\n"
            . $this->getPlainCode()
            . "});\n"
            . "</script>\n"
        ;

        return $code;
    }

    /**
     * Prints ready to use generated code.
     *
     * @return void
     */
    public function executeCode(): void
    {
        print $this->getExecuteCode();
    }
}