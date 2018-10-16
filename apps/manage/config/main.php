<?php
$params = array_merge(
    require BASE_PATH . '/common/config/params.php',
    require BASE_PATH . '/common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-manage',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'manage\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'api' => [
            'class' => 'modules\manage\api\Module',
            // ... 模块其他配置 ...
            'components'=>[
                'errorHandler' => [
                    'class' => 'heihei\rest\ErrorHandler'
                ],
                'request' => [
                    'class' => 'heihei\rest\Request',
                    'csrfParam' => '_csrf-manage-api',
                    'cookieValidationKey' => 'lEK-u27CGPsPdfJzcAaDY3AwrnO-Ozbg'
                ]
            ], 
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-manage',
        ],
        'user' => [
            'identityClass' => 'manage\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-manage', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the manage
            'name' => 'heiheisoft-manage',
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
            'errorAction' => 'site/error',
        ],

        'authManager' => [
            'class' => 'heihei\rbac\AuthManager',
            'allowPermissions' => ['site/error','site/login','site/index','site/logout','api/site/login','api/site/index']
        ],
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
                'login' => 'site/login',
                'logout' => 'site/logout',
                'api/login' => 'api/site/login'
            ],
        ],
        
    ],
    'params' => $params,
];
