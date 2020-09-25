<?php

return [
    'class' => \yii\queue\amqp_interop\Queue::class,
    'host' => 'rabbitmq',
    'port' => 5672,
    'user' => 'guest',
    'password' => 'guest',
    'queueName' => 'yii2queue',
    'driver' => \yii\queue\amqp_interop\Queue::ENQUEUE_AMQP_LIB,

    // or
    // 'dsn' => 'amqp://guest:guest@rabbitmq:5672/%2F',

    // or, same as above
    // 'dsn' => 'amqp:',
];
