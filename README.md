#### RabbitMq 包使用示例

1.**创建exchange**
```php
$config = [
    'default'   => [
        'host'      => '132.75.114.99',
        'port'      => 5672,
        'login'     => 'admin',
        'password'  => 'abc',
        'vhost'     => 'admin'
    ]
];
include './vendor/autoload.php';
try{
     $mq = new \pandaSir\Mq\Connection\AMQPConnection($config);
     $channel = $mq->channel();
     $exchange = $channel->exchange('jia.task', AMQP_EX_TYPE_TOPIC, false, true);
}catch (\Exception $e){
     var_dump($e->getMessage());die;
}
```

2.**创建队列**
```php
include './vendor/autoload.php';
try{
     $mq = new \pandaSir\Mq\Connection\AMQPConnection($config);
     $channel = $mq->channel();
     $queue   = $channel->queue('test_queue')
}catch (\Exception $e){
     var_dump($e->getMessage());die;
}
```

3.**发送消息**
```php
include './vendor/autoload.php';
try{
     $mq = new \pandaSir\Mq\Connection\AMQPConnection($config);
     $channel = $mq->channel();
     $result = $channel->basicPublish('name is jack', 'jia.task', 'test', [
             'delivery_mode' => 2
         ]);
}catch (\Exception $e){
     var_dump($e->getMessage());die;
}
```

4.**队列消费**
```php
include './vendor/autoload.php';
try{
     $mq = new \pandaSir\Mq\Connection\AMQPConnection($config);
     $channel = $mq->channel();
     $callback = function (AMQPEnvelope $msg, AMQPQueue $queue) {
         var_dump($msg->getBody());
         $queue->ack($msg->getDeliveryTag());
     };
     $channel->basicConsume('test', $callback, true); 
}catch (\Exception $e){
     var_dump($e->getMessage());die;
}
```