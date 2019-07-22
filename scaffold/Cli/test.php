<?php

include dirname(dirname(__DIR__)) . "/vendor/autoload.php";

use Scaffold\validate\post;

//if (post::validate(['name' => '998', 'nick' => '12'], 'create')) {
//	var_dump(post::getFirstError());
//}


var_dump(
    Scaffold\amqp\testAmqp::insertQueue(['index' => 'distributesalelodgeunit', 'action' => 'modify', 'args' => [123456]])
);
//
sleep(2);
var_dump(\Scaffold\amqp\testAmqp::getMessage());
