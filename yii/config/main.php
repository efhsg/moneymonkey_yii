<?php

/**
 * This is the "base" config shared across web, console, and test.
 * We do NOT load the DB here (it is loaded in each environment config).
 */

use yii\symfonymailer\Mailer;

$params = require __DIR__ . '/params.php';

$config = [
    'name' => 'MoneyMonkey',

    // Base application path
    'basePath' => dirname(__DIR__),

    // Common time zone
    'timeZone' => 'Europe/Amsterdam',

    // Bootstrap log component in all environments that merge this config
    'bootstrap' => ['log'],

    // Common aliases
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],

    'modules' => [
        'config' => [
            'class' => 'app\modules\config\ConfigModule',
        ],
        'identity' => [
            'class' => 'app\modules\identity\Module',
        ],
    ],

    // Common components (no DB here!)
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\DbTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['application', 'database'],
                    'logTable' => '{{%log}}',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                    'categories' => ['application', 'database'],
                    'logFile' => '@runtime/logs/db.log',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'defaultTimeZone' => 'Europe/Amsterdam',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [],
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
            'bundles' => [
                'yii\bootstrap5\BootstrapAsset' => [
                    'css' => [],
                ],
                'yii\bootstrap5\BootstrapPluginAsset' => [
                    'js' => [],
                ],
            ],
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\modules\identity\models\User',
            'enableAutoLogin' => true,
            'authTimeout' => 3600 * 24 * 30,
            'loginUrl' => ['/identity/auth/login'],
            'enableSession' => !Yii::$app instanceof yii\console\Application,
        ],
        'mailer' => [
            'class' => Mailer::class,
            'viewPath' => '@app/mail',
            // Send all mails to a file by default (change to `false` for real emails)
            'useFileTransport' => true,
        ],
    ],

    // Common parameters
    'params' => $params,
];

$config['container'] = [
    'definitions' => [
        'app\modules\identity\services\UserDataSeederInterface' => [
            'class' => 'app\services\UserDataSeeder',
        ],
    ],
];

return $config;