<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'shop-tracker',
    'name' => '5magaz',
    'language' => 'ru-RU',
    'timeZone' => 'Europe/Moscow',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'authManager' => [
            'class' => \yii\rbac\DbManager::class,
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ztuboMFxkAcF40T8aRzzt07xcBJlqKs7',
            'baseUrl' => '',
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
            // send all mails to a file by main. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'username' => $params['smtp']['username'],
                'password' => $params['smtp']['password'],
                'host' => $params['smtp']['host'],
                'port' => $params['smtp']['port'],
                'encryption' => false,
            ],
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'admin' => 'admin/discount/index',
                '<action>' => 'site/<action>',
            ],
        ],
        'formatter' => [
            'class' => \yii\i18n\Formatter::class,
            'locale' => 'ru.RU',
            'dateFormat' => 'dd MMMM',
            'defaultTimeZone' => 'Europe/Moscow',
        ],

        // Компоненты для работы с сайтами
        'fiveShop' => [
            'class' => \app\components\markets\FiveShop::class,
        ],
        'bristol' => [
            'class' => \app\components\markets\Bristol::class,
        ],
    ],
    'params' => $params,
    'modules' => [
        'admin' => [
            'class' => 'app\modules\admin\Admin',
        ],
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

   $config['components']['db'] = require __DIR__ .'/db-local.php';
}

return $config;
