<?php
$rootPath = __DIR__ . '/..';
require $rootPath . '/vendor/autoload.php';
require $rootPath . '/config/env.php';

defined('YII_DEBUG') or define('YII_DEBUG', (boolean)getenv('YII_DEBUG'));
define('YII_ENV_DEV', (boolean)getenv('YII_ENV_DEV'));
defined('YII_ENV') or define('YII_ENV', getenv('YII_ENV'));

require $rootPath . '/vendor/yiisoft/yii2/Yii.php';

$config      = require($rootPath . '/config/web.php');
$application = new yii\web\Application($config);
$application->run();
