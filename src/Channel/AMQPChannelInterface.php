<?php
/**
 * @author Haohuang
 * @email  huanghao1054@gmail.com
 * @since   2019/11/18
 */

namespace pandaSir\RabbitMQ\Channel;

interface AMQPChannelInterface
{
    public function queue($name, $passive = false, $durable = false, $exclusive = false, $autoDelete = false);

    public function basicPublish($message, $exchangeName, $routeKey, $attributes = []);

    public function basicConsume($queue, $callback, $autoAck = false, $exclusive = false);
}