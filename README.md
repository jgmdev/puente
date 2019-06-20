# PQuery

A PHP library that facilitates the communication between your php
code and jQuery/JavaScript code. It serves as a jQuery wrapper that
generates working javascript code. It uses ajax functionality to send
all registered browser events back to php for server side processing, then
it returns more javascript code to the client browser.

## Usage

First you will need to include the jQuery library in your html code.
Then you will have to initialize a PQuery instance as follows:

```php
$pquery = new \PQuery\PQuery();
```

To manipulate a DOM element you will use the **jq()** method which will return
a JQuery object instance that mimics the jQuery functionality.

```php
$pquery->jq("#message")->html("Hello World!");
```

You can register events as you would do with jQuery, with the difference that
you will be able to register a PHP callback.

```php
$pquery->jq(".element")->click(function($pquery, $data){
    $pquery->jq(".element")->text("Hello World!");
});
```
You can access other DOM objects like **window**, **console** and **location** 
with more planned to come.

```php
$pquery->jq(".element")->click(function($pquery, $data){
    $pquery->window()->alert("Hello World!");
});
```

Also, when calling javascript functions you may want to give it a javascript
object instead of a string, which you can achieve by using the **js:** prefix.

```php
$pquery->jq(".element")->click(function($pquery, $data){
    $pquery->window()->alert("js:window.innerWidth");
});
```

When registering an event you can tell it to fetch data from user browser by
giving json to the **$data** parameter.

```php
$pquery->jq(".element")->click(
    function($pquery, $data){
        $pquery->window()->alert($data["width"]);
    },
    "{width: window.innerWidth}" //You can also feed it php arrays and objects.
);
```

This will send the client browser window width back to the php callback.

### Listening the registered events

All events like click, dblclick, etc... will now need to be listened by your
php script, for this all you need to do is call the following method:

```php
$pquery->listenRequest();
```

This will be in charge of checking if a request to the same page that holds
the PQuery code comes from the user browser and respond to it.

### Generating the code

After writing your PQuery logic you will have to tell it to generate the 
javascript code.

```php
$pquery->executeCode(); //This actually prints the generated code to the document
```

If you dont want to directly print the code you can call 
**getExecuteCode()** instead:

```php
$code = $pquery->getExecuteCode(); //Now you can decide what to do with it
```

### Silly but working example code
--------------------------------------------------------------------------------

Here is some sample code that you can copy and paste for testing into a 
php script file.

```php
<?php
$pquery = new \PQuery\PQuery();

$pquery->jq("#message")->html("Hello World!")->click(function($pquery, $data){
    $system = `uname -a`;
    $pquery->jq("#message")->html($system)
        ->css(array("position" => "relative"))
        ->animate(array("top" => "+=20px"))
    ;

    if($data["width"] > 500)
    {
        $pquery->jq("#message")->toggle("slow", function($pquery, $data){
            if($data["visible"] == "false")
            {
                $pquery->jq("#message")->toggle(
                    "slow",
                    function($pquery, $data){
                        $pquery->jq("#message")->html("keep it open!");
                    }, 
                    "{status: 'test'}"
                );
            }
            else
            {
                $pquery->jq("#message")->html(":)");
            }
        }, "{visible: $('#message').is(':visible')}");
    }
}, "{width: $(window).width()}");

$pquery->jq("#message")->css(
    ["border" => "solid 1px #000", "cursor" => "pointer"]
);

$pquery->jq("window")->resize(function($pquery, $data){
    $pquery->window()->console()->log($data["width"]);
}, "{width: $(window).width()}");

$pquery->listenRequest();
$pquery->executeCode();
?>

<div id="message"></div>
```

## Status

The library is still **under development** but it is already proving to be
useful. Many stuff still needs to be added.