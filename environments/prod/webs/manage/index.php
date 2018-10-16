<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'prod');
defined('BASE_PATH') or define('BASE_PATH', dirname(dirname(__DIR__)));
defined('APP_BASE_PATH') or define('APP_BASE_PATH', BASE_PATH . '/apps/manage');

require BASE_PATH . '/vendor/autoload.php';
require BASE_PATH . '/vendor/yiisoft/yii2/Yii.php';
require BASE_PATH . '/common/config/bootstrap.php';
require APP_BASE_PATH . '/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require BASE_PATH . '/common/config/main.php',
    require BASE_PATH . '/common/config/main-local.php',
    require APP_BASE_PATH . '/config/main.php',
    require APP_BASE_PATH . '/config/main-local.php'
);

(new yii\web\Application($config))->run();
