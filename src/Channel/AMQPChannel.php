<?php
/**
 * @author Haohuang
 * @email  huanghao1054@gmail.com
 * @since   2019/11/16
 */

namespace pandaSir\RabbitMQ\Channel;


class AMQPChannel implements AMQPChannelInterface
{
    /**
     * @var \AMQPChannel
     */
    protected $channel;

    /**
     * Channel constructor.
     * @param \AMQPChannel $channel
     */
    public function __construct(\AMQPChannel $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/16
     * @param $name
     * @param string $type
     * @param string $passive
     * @param bool $durable
     * @param bool $autoDelete
     * @throws \Exception
     * @return \AMQPExchange
     */
    public function exchange($name, $type = AMQP_EX_TYPE_TOPIC, $passive = false, $durable = false, $autoDelete = false)
    {
        $exchange = new \AMQPExchange($this->channel);
        $exchange->setName($name);
        $exchange->setType($type);

        if( $passive === true ) {
            $exchange->setFlags(AMQP_PASSIVE);
        }

        if( $durable === true ) {
            $exchange->setFlags(AMQP_DURABLE);
        }

        if( $autoDelete === true ) {
            $exchange->setFlags(AMQP_AUTODELETE);
        }

        $exchange->declareExchange();
        return $exchange;
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/16
     * @param $name
     * @throws \Exception
     * @return boolean
     */
    public function exchangeDelete($name)
    {
        $exchange = new \AMQPExchange($this->channel);
        $exchange->setName($name);
        return $exchange->delete();
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param string $name
     * @param boolean $passive
     * @param boolean $durable
     * @param boolean $exclusive
     * @param boolean $autoDelete
     * @throws \Exception
     * @return \AMQPQueue
     */
    public function queue($name, $passive = false, $durable = true, $exclusive = false, $autoDelete = false)
    {
        $queue = new \AMQPQueue($this->channel);
        $queue->setName($name);

        if( $passive === true ) {
            $queue->setFlags(AMQP_PASSIVE);
        }

        if( $durable === true ){
            $queue->setFlags(AMQP_DURABLE);
        }

        if( $exclusive === true ) {
            $queue->setFlags(AMQP_EXCLUSIVE);
        }

        if( $autoDelete === true ) {
            $queue->setFlags(AMQP_AUTODELETE);
        }

        $queue->declareQueue();
        return $queue;
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param $queueName
     * @param $exchangeName
     * @param string $routeKey
     * @throws \Exception
     * @return boolean
     */
    public function queueBind($queueName, $exchangeName, $routeKey = null)
    {
        $queue = new \AMQPQueue($this->channel);
        $queue->setName($queueName);
        $queue->setFlags(AMQP_PASSIVE);
        return $queue->bind($exchangeName, $routeKey);
    }

    /**
     * Publish message
     *
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param string $message
     * @param string $exchangeName
     * @param string $routeKey
     * @param array $attributes
     * @throws \Exception
     */
    public function basicPublish($message, $exchangeName, $routeKey, $attributes = [])
    {
        $exchange = $this->exchange($exchangeName, AMQP_EX_TYPE_TOPIC, true);
        return $exchange->publish($message, $routeKey, AMQP_NOPARAM, $attributes);
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param string $queue
     * @param callable $callback
     * @param bool $autoAck
     * @param bool $exclusive
     * @throws \Exception
     */
    public function basicConsume($queue, $callback, $autoAck = false, $exclusive = false)
    {
        $flag = $autoAck === true ? AMQP_AUTOACK : AMQP_NOPARAM;
        $queue = $this->queue($queue, true, true);
        $queue->consume(function (\AMQPEnvelope $envelope) use ($queue, $callback) {
            $callback($envelope, $queue);
        }, $flag);
    }

}