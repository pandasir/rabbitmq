<?php
/**
 * @author Haohuang
 * @email  huanghao1054@gmail.com
 * @since   2019/11/16
 */

namespace pandaSir\RabbitMQ\Connection;


use pandaSir\RabbitMQ\Channel\AMQPChannel;

class AMQPConnection extends AbstractConnection
{
    /**
     * @var array
     */
    protected $config;

    /**
     * Client constructor.
     * @param $config
     * @throws \AMQPConnectionException
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->connection($config['default']);
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/16
     * @throws \AMQPConnectionException
     * @return AMQPChannel
     */
    public function channel()
    {
        return new AMQPChannel(new \AMQPChannel($this->connection));
    }

}