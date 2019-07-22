<?php

namespace Scaffold\package;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPChannelClosedException;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

abstract class BasicRabbitmq
{

    private static $connection = null;
    private static $channel = [];

    const EXCHANGE_NAME = NULL;
    const EXCHANGE_TYPE = AMQPExchangeType::DIRECT;

    const QUEUE_NAME =  NULL;

    const ROUTE_KEY = NULL;

    const BIND_KEY = NULL;


    const RECONNECT_COUNT = 5;

    private static function getConnection($force = false)
    {
        if (!self::$connection || $force) {
           self::$connection = new AMQPStreamConnection('192.168.27.187', '5672', 'admin', 'admin');
        }
        return self::$connection;
    }

    private static function getChannel($force = false)
    {
        $singleKey = md5(static::class);
        if (!isset(self::$channel[$singleKey]) || $force) {
            $channel = self::getConnection($force)->channel();

            $channel->exchange_declare(static::EXCHANGE_NAME, static::EXCHANGE_TYPE, false, true, false);
            $channel->queue_declare(static::QUEUE_NAME, false, true, false, false);

            $channel->queue_bind(static::QUEUE_NAME, static::EXCHANGE_NAME, static::BIND_KEY);

            self::$channel[$singleKey] = $channel;
        }

        return self::$channel[$singleKey];
    }

    public static function insertQueue(array $msg, $routeKey = null)
    {
        $trynum = 0;
        while ($trynum < self::RECONNECT_COUNT) {
            try{
                $message = new AMQPMessage(json_encode($msg, JSON_UNESCAPED_UNICODE), [
                    'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
                ]);

                self::getChannel()->basic_publish($message, static::EXCHANGE_NAME, $routeKey ?? static::ROUTE_KEY);

                self::getChannel()->close();
                self::getConnection()->close();

                return true;
            }catch (AMQPChannelClosedException $e) {
                usleep(500);
                $trynum++;
                self::getChannel(true);
            }
        }

        return false;
    }

    abstract static function buildResult($body);

    public static function getMessage($queueName = null)
    {
        $trynum = 0;
        while ($trynum < self::RECONNECT_COUNT) {
            try{
                $message = self::getChannel()->basic_get($queueName ?? static::QUEUE_NAME);

                if ($message) {
                    self::getChannel()->basic_ack($message->delivery_info['delivery_tag']);
                    return static::buildResult($message->body);
                }

                return null;
            }catch (AMQPChannelClosedException $e) {
                usleep(500);
               $trynum++;
               self::getChannel(true);
            }

        }

        return false;
    }
}