<?php

use yii\db\Connection;

return [
    'class' => Connection::class,
    'dsn' => 'pgsql:host=db;port=5432;dbname=testInsta',
    'username' => 'testInsta',
    'password' => 'testInsta',
    'charset' => 'utf8',
    'tablePrefix' => '',
    'enableSchemaCache' => YII_ENV_PROD ? true : false,
];
