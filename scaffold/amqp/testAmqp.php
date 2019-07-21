<?php
namespace Scaffold\amqp;

use Scaffold\package\BasicRabbitmq;

class testAmqp extends BasicRabbitmq
{
    const EXCHANGE_NAME = "x-test.exchange-direct";

    const QUEUE_NAME = "q-test.queue";

    const BIND_KEY = "r-test.route";
    const ROUTE_KEY = "r-test.route";

    public static function buildResult($body)
    {
        var_dump($body);

    }
}