<?php
/*
 * @author Jefferson González
 * @license MIT
 * @link https://github.com/jgmdev/puente Source code.
 */

namespace Puente;

/**
 * Serves as a proxy between PHP and jQuery for easier communication between
 * both. Also is in charge of generating the javascript code and listening
 * for events on the client browser, processing them and sending more javascript
 * code to the client browser.
 */
class Puente
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
    private $instance = 0;
    private $run_first_time = true;
    private $debug_mode = false;
    private $post_url = "";

    private static $next_instance = 1;

    /**
     * Represents the DOM window element.
     * @var \Puente\DOM\Window
     */
    private $window;

    /**
     * Represents the DOM localStorage element.
     * @var \Puente\DOM\LocalStorage
     */
    private $local_storage;

    /**
     * Represents the DOM sessionStorage element.
     * @var \Puente\DOM\SessionStorage
     */
    private $session_storage;

    /**
     * Represents the DOM localStorage element.
     * @var \Puente\DOM\PuenteStorage
     */
    private $puente_storage;

    /**
     * Constructor
     * @param string $post_url If set, uses this url to send client events
     * instead of using window.location.href.
     */
    public function __construct(string $post_url="")
    {
        $this->instance = self::$next_instance;
        self::$next_instance++;

        $this->post_url = $post_url;

        $this->window = new DOM\Window($this);

        $this->local_storage = new DOM\LocalStorage($this);

        $this->session_storage = new DOM\SessionStorage($this);

        $this->puente_storage = new DOM\PuenteStorage($this);
    }

    private function getPostLocation(): string
    {
        if($this->post_url)
            return '"' . $this->post_url . '"';
        else
            return "window.location.href";
    }

    /**
     * Starts a new buffer to store generated code. This is used when
     * generating code inside callbacks.
     *
     * @return void
     */
    private function createBuffer(): void
    {
        $level = $this->code_buffer_next_level;
        $this->code_buffer_level = $level;

        $this->code_buffer[$level] = [];
        $this->code_buffering = true;

        $this->code_buffer_next_level++;
    }

    /**
     * Destroy previously created buffer with createBuffer().
     *
     * @return void
     */
    private function destroyBuffer(): void
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

    /**
     * Clears the active buffer.
     *
     * @return void
     */
    private function clearBuffer(): void
    {
        if($this->code_buffering)
        {
            $level = $this->code_buffer_level;
            $this->code_buffer[$level] = [];
        }
    }

    /**
     * Helper to generate the code needed to retrieve the apropiate parent
     * for a callback. This is needed to execute all parents that will then
     * create the child callback.
     *
     * @param int $id
     * @param string $data
     * @return array
     */
    private function getParents(int $id, string $data="{}"): array
    {
        if($this->code_buffering)
        {
            return [
                "decl" => "\nowner=this;"
                    . "parents.push(parent_id);"
                    . "parents_data[parent_id] = parent_data;"
                    . "parent_id=$id;"
                    . "parent_data=$data;",
                "call" => "parents: parents, parents_data: parents_data,"
            ];
        }
        else
        {
            return [
                "decl" => "\nvar owner=this;"
                    . "var parents=[];"
                    . "var parents_data=[];"
                    . "var parent_id=$id;"
                    . "var parent_data=$data;",
                "call" => ""
            ];
        }

        return [];
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
                ["'", "\n"],
                ["\\'", "\\n"],
                $selector
            )
            . "'"
        ;
    }

    /**
     * Logs in browser console the javascript code sent by callbacks.
     *
     * @return \Puente\Puente
     */
    public function enableDebug(): self
    {
        $this->debug_mode = true;

        return $this;
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

        if($selector == "js:this" && $this->code_buffering)
        {
            $selector .= $this->current_element;
            $selector_parsed = "owner";
        }

        if(!isset($this->elements[$selector]))
        {
            $varname = "ele".$this->current_element;

            $jquery = new JQuery(
                $varname, $this
            );

            $this->addCode("var $varname = jq($selector_parsed);");

            $this->elements[$selector] = [
                "var" => $varname,
                "object" => $jquery
            ];

            $this->current_element++;

            return $jquery;
        }

        return $this->elements[$selector]["object"];
    }

    /**
     * Add hand crafted javascript code.
     *
     * @param string $code
     * @param string $identifier
     *
     * @return \Puente\Puente
     */
    public function addCode(string $code, string $identifier=""): self
    {
        if(!$this->code_buffering)
        {
            if($identifier)
                $this->code[$identifier] = $code;
            else
                $this->code[] = $code;
        }
        else
        {
            if($identifier)
                $this->code_buffer[$this->code_buffer_level][$identifier] = $code;
            else
                $this->code_buffer[$this->code_buffer_level][] = $code;
        }

        return $this;
    }

    /**
     * Get hand crafted code stored with a specific identifier.
     *
     * @param string $code
     *
     * @return string
     */
    public function getCode(string $identifier): string
    {
        if(!$this->code_buffering)
        {
            return $this->code[$identifier] ?? "";
        }

        return $this->code_buffer[$this->code_buffer_level][$identifier]
            ??
            ""
        ;
    }

    /**
     * Gives you access to the window DOM object.
     *
     * @return \Puente\DOM\Window
     */
    public function window(): DOM\Window
    {
        return $this->window;
    }

    /**
     * Gives you access to the localStorage object.
     *
     * @return \Puente\DOM\LocalStorage
     */
    public function localStorage(): DOM\LocalStorage
    {
        return $this->local_storage;
    }

    /**
     * Gives you access to the sessionStorage object.
     *
     * @return \Puente\DOM\SessionStorage
     */
    public function sessionStorage(): DOM\SessionStorage
    {
        return $this->session_storage;
    }

    /**
     * Gives you access to the Puente array object.
     *
     * @return \Puente\DOM\PuenteStorage
     */
    public function puenteStorage(): DOM\PuenteStorage
    {
        return $this->puente_storage;
    }

    /**
     * Generates an ajax callback back to the server.
     *
     * @param callable $callback
     * @param string|array|object $data A valid json string or php array/object.
     *
     * @return \Puente\Puente
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

        $instance = $this->instance;
        $parents = $this->getParents($id, $data);

        $debug = "";
        if($this->debug_mode)
        {
            $debug .= "console.log(data.code);";
        }

        $code = $parents["decl"]
            . "jq.ajax("
            . "{"
            . "type: 'POST', "
            . "url: ".$this->getPostLocation().", "
            . "dataType: 'json', "
            . "data: {"
            . "puente: $instance, {$parents['call']} "
            . "id: '$id', data: $data"
            . "}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . $debug
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
     * @return \Puente\Puente
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

        $instance = $this->instance;
        $parents = $this->getParents($id, $data);

        $debug = "";
        if($this->debug_mode)
        {
            $debug .= "console.log(data.code);";
        }

        $callback_code = "function(event){"
            . $parents["decl"]
            . "jq.ajax("
            . "{"
            . "type: 'POST', "
            . "url: ".$this->getPostLocation().", "
            . "dataType: 'json', "
            . "data: {"
            . "puente: $instance, {$parents['call']} "
            . "id: '$id', data: $data"
            . "}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . $debug
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
     * @param string $identifier Chain the event to the given identifier.
     *
     * @return \Puente\Puente
     */
    public function addElementEvent(
        string $varname,
        string $type,
        callable $callback,
        $data="{}",
        string $identifier=""
    ): self
    {
        $id = $this->current_event;
        $this->current_event++;

        $this->events[$id] = $callback;

        if(is_array($data) || is_object($data))
        {
            $data = json_encode($data);
        }

        $instance = $this->instance;
        $parents = $this->getParents($id, $data);

        $debug = "";
        if($this->debug_mode)
        {
            $debug .= "console.log(data.code);";
        }

        $varname_include = $varname;
        if($identifier)
        {
            $varname_include = "";
        }

        $code = "$varname_include.on('$type', function(event){"
            . $parents["decl"]
            . "event.preventDefault();"
            . "jq.ajax("
            . "{"
            . "type: 'POST', "
            . "url: ".$this->getPostLocation().", "
            . "dataType: 'json', "
            . "data: {"
            . "puente: $instance, {$parents['call']} id: '$id', "
            . "element: '$varname', data: $data"
            . "}"
            . "}"
            . ").done(function( data ) {"
            . "if(data.error){"
            . "alert(data.error);"
            . "}else{"
            . "eval(data.code);"
            . $debug
            . "}"
            . "}).fail(function(data){"
            . "alert('Error Occurred');"
            . "});"
            . "});"
        ;

        if(!$identifier)
        {
            $this->addCode($code);
        }
        else
        {
            $current_code = rtrim($this->getCode($identifier), ";");

            if($current_code)
            {
                $current_code .= $code;
            }
            else
            {
                $current_code = $varname . $code;
            }

            $this->addCode($current_code, $identifier);
        }

        return $this;
    }

    /**
     * Process events made from the browser.
     *
     * @return void
     */
    public function listenRequest(): void
    {
        if(
            isset($_POST["puente"])
            &&
            $_POST["puente"] == $this->instance
        )
        {
            // This only works if output buffering is enabled with ob_start(),
            // the idea is to Remove previously echoed/html output in order to
            // return clean json output.
            ob_clean();

            header('Content-Type: application/json; charset=utf-8', true);

            $data = [];
            $puente = $this;

            if(isset($_POST["id"]))
            {
                $id = intval($_POST["id"]);

                if(isset($this->events[$id]))
                {
                    $callback = $this->events[$id];

                    $puente->createBuffer();

                    $callback(
                        $puente,
                        $_POST["data"] ?? array()
                    );

                    $data["code"] = $puente->getPlainCode();

                    $puente->destroyBuffer();
                }
                else
                {
                    if(isset($_POST["parents"]))
                    {
                        $puente->createBuffer();

                        foreach($_POST["parents"] as $parent)
                        {
                            $data = [];

                            if(
                                isset($_POST["parents_data"])
                                &&
                                isset($_POST["parents_data"][$parent])
                            )
                            {
                                $data = $_POST["parents_data"][$parent];
                            }

                            $this->events[$parent](
                                $puente, $data
                            );
                        }

                        $puente->clearBuffer();

                        if(isset($this->events[$id]))
                        {
                            $callback = $this->events[$id];

                            $callback(
                                $puente,
                                $_POST["data"] ?? array()
                            );

                            $data["code"] = $puente->getPlainCode();
                        }
                        else
                        {
                            $data["error"] = "No child id registered.";
                        }

                        $puente->destroyBuffer();
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
     * Get the instance ID of the puente.
     *
     * @return integer
     */
    public function getInstanceID(): int
    {
        return $this->instance;
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
            // Initialize global storage of variables.
            $storage = "";
            if($this->run_first_time)
            {
                $storage .= "Puente{$this->instance} = [];\n"
                    . "  "
                ;

                $this->run_first_time = false;
            }

            $code .= "(function(jq) {\n"
                . "  " //indentation
                . $storage
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