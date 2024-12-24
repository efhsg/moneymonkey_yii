<?php

use yii\helpers\ArrayHelper;

$main = require __DIR__ . '/main.php';
$params = require __DIR__ . '/params.php';

$config = [
    'id'   => 'basic',
    'name' => 'MoneyMonkey',

    'components' => [
        'db' => require __DIR__ . '/db.php',

        'request' => [
            'cookieValidationKey' => 'IwE5i3d_0AhHc5a7gnVMSk38YDzgqBYi',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout'     => 3600 * 24 * 30,
            'loginUrl'        => ['/login/login'],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'js' => [],
                ],
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'rules'           => [],
        ],
    ],

    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '172.*.*.*'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1', '172.*.*.*'],
    ];
}

return ArrayHelper::merge($main, $config);
