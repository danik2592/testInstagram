<?php

use yii\console\controllers\MigrateController;

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');
Yii::setAlias('@migrations', dirname(__DIR__) . '/migrations');
$params = require(__DIR__ . '/params.php');
$db = require(__DIR__ . '/db.php');

return [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['webpack'],
    'controllerNamespace' => 'app\commands',
    'controllerMap' => [
        'instagram' => \app\commands\InstagramController::class,
        'migrate' => [
            'class' => MigrateController::class,
            'migrationPath' => null, // disable non-namespaced migrations if app\migrations is listed below
            'migrationNamespaces' => [
                '@vendor/dektrium/yii2-user/migrations',
                '@migrations',
            ],
        ],
    ],
    'modules' => [
        'gii' => 'yii\gii\Module',
        'user' => [
            'class' => 'dektrium\user\Module',
        ],
        'webpack' => [
            'class' => \sweelix\webpack\Module::class,
        ],
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'db' => $db,
    ],
    'params' => $params,
];
