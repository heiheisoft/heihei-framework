<?php
$params = array_merge(
    require BASE_PATH . '/common/config/params.php',
    require BASE_PATH . '/common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);
$queueClosure = function($app){return $app->getModule('queue');};
return [
    'id' => 'app-rest',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'rest\controllers',
    'bootstrap' => ['log', $queueClosure],
    'modules' => [
        'queue' => [
            'class' => 'modules\rest\queue\Module',
        ],
    ],
    'components' => [
        'request' => [
            'class' => 'heihei\rest\Request',
            'csrfParam' => '_csrf-rest',
        ],
        'user' => [
            'loginUrl' => null,
            'identityClass' => 'rest\models\User',
            'enableSession' => false,
            'identityCookie' => ['name' => '_identity-rest', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the manage
            'name' => 'heiheisoft-rest',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'class' => 'heihei\rest\ErrorHandler',
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [],
        ],
        
    ],
    'params' => $params,
];
