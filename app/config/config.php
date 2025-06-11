<?php

use yii\rbac\DbManager;

$config = [
    'bootstrap'  => ['log'],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'basePath'   => BASE_PATH,
    'timeZone'   => 'Europe/Moscow',
    'name'       => '',
    'components' => [
        'log'         => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'          => [
            'class'       => 'yii\db\Connection',
            'dsn'         => 'mysql:host=127.0.0.1;dbname=dbname',
            'username'    => 'root',
            'password'    => 'root',
            'charset'     => 'utf8',
        ],
//        'authManager' => [
//            'class' => DbManager::class,
//        ],
        'mailer'      => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'cache'       => [
            'class' => \yii\caching\FileCache::class,
        ],
//        'cache' => [ // Redis
//            'class' => \App\Cache\Redis::class,
//            'host' => '127.0.0.1',
//            'database' => 0,
//            'port' => 6379,
//            'password' => '',
//        ],
    ],
    'params'     => require(__DIR__ . '/params.php'),
];

return $config;