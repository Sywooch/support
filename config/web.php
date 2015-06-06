<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'components' => [
        /*'formatter' => [
           'class' => 'yii\i18n\Formatter',
           'timeZone' => 'Europe/Moscow',
           'dateFormat' => 'php:d.m.Y',
           'datetimeFormat' => 'php:d.m.Y H:i',
           'timeFormat' => 'php:H:i'
        ],
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['user','moder','admin'], //здесь прописываем роли
            //зададим куда будут сохраняться наши файлы конфигураций RBAC
            'itemFile' => '@common/components/rbac/items.php',
            'assignmentFile' => '@common/components/rbac/assignments.php',
            'ruleFile' => '@common/components/rbac/rules.php'
        ],*/
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
                // site
                '/' => 'site/index',
                '/about' => 'site/about',
                '/contact' => 'site/contact',
                '/captcha' => 'site/captcha',
                '/login' => 'site/login',
                '/logout' => 'site/logout',

                // orders
                'orders' => 'order/index',
                'order/<id:\d+>' => 'order/view',
                'order/create' => 'order/create',
                'order/update/<id:\d+>' => 'order/update',
                'order/delete/<id:\d+>' => 'order/delete',

                // users
                'users' => 'user/index',
                'user/<id:\d+>' => 'user/view',
                'user/create' => 'user/create',
                'user/update/<id:\d+>' => 'user/update',
                'user/delete/<id:\d+>' => 'user/delete',

                // rest api
                '/api/orders/get'   => 'api/list',
                '/api/order/update' => 'api/update',
                '/api/order/delete' => 'api/delete',
                '/api/user/auth'    => 'api/auth'
            ]
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'yJXlOaqfzyWNFwntgAfQLUp6_HX4AnU-',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser'
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            /*'transport' => [
 *             'class' => 'Swift_SmtpTransport',
 *             'host' => 'localhost',
 *             'username' => 'username',
 *             'password' => 'password',
 *             'port' => '587',
 *             'encryption' => 'tls',
 *          ]*/
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
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = 'yii\debug\Module';

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
