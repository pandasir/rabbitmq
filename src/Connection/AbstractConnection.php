<?php
/**
 * @author Haohuang
 * @email  huanghao1054@gmail.com
 * @since   2019/11/18
 */

namespace pandaSir\RabbitMQ\Connection;


abstract class AbstractConnection
{
    /**
     * @var array
     */
    protected $current_connection;

    /**
     * @var \AMQPConnection
     */
    protected $connection;

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param $options
     * @throws \AMQPConnectionException
     */
    public function connection($options)
    {
        $this->connection = new \AMQPConnection($options);
        $this->connection->connect();
        $this->current_connection = $options;
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @param $options
     * @throws \AMQPConnectionException
     */
    public function setConnection($options)
    {
        $this->connection($options);
        return $this;
    }

    /**
     * @author Haohuang
     * @email  huanghao1054@gmail.com
     * @since   2019/11/18
     * @return \AMQPConnection
     */
    public function getConnection()
    {
        return $this->connection;
    }

}