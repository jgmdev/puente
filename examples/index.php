<?php

chdir(__DIR__);

require "../lib/Autoloader.php";

// We need this if outputting html code or anything else before the
// $pquery->listenRequest() is called
ob_start();

?>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<div id="message"></div>

<?php
$pquery = new \PQuery\PQuery();

$pquery->jq("#message")->html("Hello World!")->click(function($pquery, $data){
    $system = `uname -a`;
    $pquery->jq("#message")->html($system)
        ->css(["position" => "relative"])
        ->animate(["top" => "+=20px"])
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

$pquery->jq("js:window")->resize(function($pquery, $data){
    $pquery->window()->console()->log($data["width"]);
}, "{width: $(window).width()}");

$pquery->listenRequest();

$pquery->executeCode();
