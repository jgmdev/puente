<?php

chdir(__DIR__);

require "../lib/Autoloader.php";

// We need this if outputting html code or anything else before the
// $puente->listenRequest() is called
ob_start();

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<div id="message"></div>

<?php
$puente = new \Puente\Puente();

$puente->jq("#message")->html("Hello World!")->click(function($puente, $data){
    $system = `uname -a`;
    $puente->jq("#message")->html($system)
        ->css(["position" => "relative"])
        ->animate(["top" => "+=20px"])
    ;

    if($data["width"] > 500)
    {
        $puente->jq("#message")->toggle("slow", function($puente, $data){
            if($data["visible"] == "false")
            {
                $puente->jq("#message")->toggle(
                    "slow",
                    function($puente, $data){
                        $puente->jq("#message")->html("keep it open!");
                    },
                    "{status: 'test'}"
                );
            }
            else
            {
                $puente->jq("#message")->html(":)");
            }
        }, "{visible: $('#message').is(':visible')}");
    }
}, "{width: $(window).width()}");

$puente->jq("#message")->css(
    ["border" => "solid 1px #000", "cursor" => "pointer"]
);

$puente->jq("js:window")->resize(function($puente, $data){
    $puente->window()->console()->log($data["width"]);
}, "{width: $(window).width()}");

$puente->listenRequest();

$puente->executeCode();
