# Puente :bridge_at_night:

**Puente** which is the spanish word for "*bridge*", is a PHP library that facilitates the communication between your php code and jQuery/JavaScript 
code. It serves as a jQuery wrapper that generates working JavaScript code. 
It uses ajax functionality to send all registered browser events back to php 
for server side processing, then it returns more JavaScript code to the client browser.

## Why?

Today's web development process can be tedious. You can achieve the same goal
in 1000 different ways, many JavaScript libraries exist that facilitate the
communication between your frontend and backend. Examples of these libraries
are:

* Angular
* Aurelia
* Backbone.js
* Ember.js
* Meteor
* Mithril
* React
* Vue.js
* Polymer
* (insert more frameworks here... :trollface:)

Many stuff exists and keeps proliferating, making a web developer life harder.
Knowledge doesn't comes for free and you will have to invest time learning these
JavaScript frameworks. While this is yet another project that helps you achieve
the same goal, it is based on the solid jQuery library that has been around for
many years now. jQuery is easy to learn and almost every web developer has
worked with it.

What this project does is ease the communication between the user browser
and your backend. This project is not intended to remove the need of writing
JavaScript code, but to make it easier to interact with the frontend from the
backend without needing to implement a Web API for every basic stuff you have
to do.

For a better idea of what this project has to offer keep reading below.

## Usage

First you will need to include the jQuery library in your html code:

```html
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
```

Then you will have to initialize a Puente instance:

```php
$puente = new \Puente\Puente();
```

To manipulate a DOM element you will use the **jq()** method which will return
a JQuery object instance that mimics the jQuery functionality:

```php
$puente->jq("#message")->html("Hello World!");
```

You can register events as you would do with jQuery, with the difference that
you will be able to register a PHP callback:

```php
$puente->jq(".element")->click(function($puente, $data){
    $puente->jq(".element")->text("Hello World!");
});
```

You can access other DOM objects like **window**, **console** and **location**
with more planned to come:

```php
$puente->jq(".element")->click(function($puente, $data){
    $puente->window()->alert("Hello World!");
});
```

Also, when calling JavaScript functions you may want to give it a JavaScript
object instead of a string, which you can achieve by using the **js:** prefix:

```php
$puente->jq(".element")->click(function($puente, $data){
    // This will actually show the value of window.innerWidth instead
    // of literally showing the string.
    $puente->window()->alert("js:window.innerWidth");
});
```

When registering an event you can tell it to fetch data from user browser by
giving a valid JSON string to the **$data** parameter:

```php
$puente->jq(".element")->click(
    function($puente, $data){
        $puente->window()->alert($data["width"]);
    },
    "{width: window.innerWidth}" //You can also feed it php arrays and objects.
);
```

This will send the client browser window width back to the php callback.

### Listening the registered events

All events like click, dblclick, etc... will now need to be listened by your
php script, for this all you need to do is call the following method:

```php
$puente->listenRequest();
```

This will be in charge of checking if a request to the same page that holds
the Puente code comes from the user browser and respond to it.

### Generating the code

After writing your Puente logic you will have to tell it to generate the
JavaScript code:

```php
$puente->executeCode(); //This actually prints the generated code to the document
```

If you dont want to directly print the code you can call
**getExecuteCode()** instead:

```php
$code = $puente->getExecuteCode(); //Now you can decide what to do with it
```

## Examples

To test the examples you will need PHP CLI to be installed and available on
your system path, then open a terminal and inside the **puente** project folder 
do the following:

```sh
cd examples
./run.sh
```

After running the **run.sh** script open your web browser and point
it to the returned address on your terminal which by default is:

> http://localhost:8383


## Status

The library is still **under development** but it is already proving to be
useful. Many stuff still needs to be added.

## TODO

Add more examples and use the index.php file as the main starting
point that list all the available examples.