<?php

/**
 * This is the "base" config shared across web, console, and test.
 * We do NOT load the DB here (it is loaded in each environment config).
 */

$params = require __DIR__ . '/params.php';

return [
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

    // Example of a shared module (from your web config)
    'modules' => [
        'config' => [
            'class' => 'app\modules\config\ConfigModule',
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
        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@app/mail',
            // Send all mails to a file by default (change to `false` for real emails)
            'useFileTransport' => true,
        ],
    ],

    // Common parameters
    'params' => $params,
];
