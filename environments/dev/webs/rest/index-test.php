<?php

// NOTE: Make sure this file is not accessible when deployed to production
if (!in_array(@$_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1'])) {
    die('You are not allowed to access this file.');
}
error_reporting(E_ALL);
ini_set('display_errors','On');
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

defined('BASE_PATH') or define('BASE_PATH', dirname(dirname(__DIR__)));
defined('APP_BASE_PATH') or define('APP_BASE_PATH', BASE_PATH . '/apps/rest');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require BASE_PATH . '/common/config/bootstrap.php';
require APP_BASE_PATH . '/config/bootstrap.php';

$config = require APP_BASE_PATH . '/config/test-local.php';

(new yii\web\Application($config))->run();
