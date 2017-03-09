<?php

return [
    'id' => 'yii2-audit-web',
    'name' => 'Yii2 Audit Demo',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'tests\app\controllers',
    'bootstrap' => [
        'musingsz\yii2\audit\Bootstrap',
        'audit',
    ],
    'extensions' => require(VENDOR_DIR . '/yiisoft/extensions.php'),
    'aliases' => [
        '@vendor' => VENDOR_DIR,
        '@bower' => VENDOR_DIR . '/bower-asset',
        '@musingsz/yii2/audit' => realpath(__DIR__ . '../../../../src'),
    ],
    'params' => [
        'supportEmail' => 'test@example.com',
    ],
    'components' => [
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
        ],
        'cache' => [
            'class' => YII_ENV == 'heroku' ? 'yii\caching\FileCache' : 'yii\caching\DummyCache',
        ],
        'db' => require __DIR__ . '/db.php',
        'errorHandler' => [
            'class' => '\musingsz\yii2\audit\components\web\ErrorHandler',
            'errorAction' => 'site/error',
        ],
        'log' => [
            'traceLevel' => getenv('YII_TRACE_LEVEL'),
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info', 'trace'],
                    'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION'],
                    'logFile' => YII_ENV == 'heroku' ? 'php://stderr' : '@app/runtime/logs/web.log',
                    'dirMode' => 0777
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true
        ],
        'request' => [
            'enableCsrfValidation' => false,
            'enableCookieValidation' => false
        ],
        'urlManager' => [
            'enablePrettyUrl' => getenv('YII_ENV') == 'heroku' ? true : false,
            'showScriptName' => getenv('YII_ENV') == 'heroku' ? false : true,
        ],
        'user' => [
            'identityClass' => 'tests\app\models\User',
        ],
    ],
    'modules' => [
        'audit' => [
            'class' => 'musingsz\yii2\audit\Audit',
            'accessIps' => null,
            'accessUsers' => null,
            'accessRoles' => null,
            'compressData' => YII_ENV == 'heroku' ? true : false,
            'trackActions' => ['*'],
            'ignoreActions' => [
                'site/index',
                'audit/*',
            ],
            'panelsMerge' => [
                'audit/asset' => ['class' => 'musingsz\yii2\audit\panels\AssetPanel'],
                'audit/config' => ['class' => 'musingsz\yii2\audit\panels\ConfigPanel'],
                'app/views' => ['class' => 'tests\app\panels\ViewsPanel'],
            ],
        ],
    ],
];
