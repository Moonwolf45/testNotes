<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => '3_notesProject',
    'name' => $params['title'],
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'timeZone' => 'Asia/Yekaterinburg',
    'defaultRoute' => 'note/index',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'on beforeRequest' => function ($event) {
        \yii\helpers\Inflector::$transliterator = 'Russian-Latin/BGN; Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();';
    },
    'components' => [
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '6979019',
                    'clientSecret' => 'YxkOtgrQdo1Ib3O5DjEZ',
                    'scope' => 'email',
                    'apiVersion' => '5.199'
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'locale' => 'ru_RU',
            'timeZone' => 'Asia/Yekaterinburg',
            'dateFormat' => 'dd.MM.yyyy',
            'timeFormat' => 'H:mm:ss',
            'datetimeFormat' => 'dd.MM.yyyy H:mm:ss',
            'decimalSeparator' => ',',
            'thousandSeparator' => ' ',
            'currencyCode' => 'RUB',
        ],
        'request' => [
            'cookieValidationKey' => 'U43bcSmK39-Mt6cyALdNdrhL8d9iAOrv',
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
            'class' => \yii\symfonymailer\Mailer::class,
            'transport' => [
                'scheme' => 'smtps',
                'host' => '',
                'username' => '',
                'password' => '',
                'port' => 465,
                'dsn' => 'native://default',
            ],
            'useFileTransport' => false,
            'viewPath' => '@app/mail',
            'htmlLayout' => 'layouts/html',
            'textLayout' => 'layouts/text',
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['info@notes.ru' => $params['title']],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap5\BootstrapAsset' => [
                    'sourcePath' => '@bower/bootstrap/dist',
                    'css' => [
                        YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css',
                    ]
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'sourcePath' => '@bower/bootstrap/dist',
                    'js' => [
                        YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js',
                    ]
                ],
                'yii\web\JqueryAsset' => [
                    'js' => [
                        YII_ENV_DEV ? 'jquery.js' : 'jquery.min.js',
                    ]
                ]
            ]
        ]
    ],
    'params' => $params
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
}

return $config;
