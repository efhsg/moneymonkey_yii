<?php

use yii\helpers\ArrayHelper;
use yii\symfonymailer\Mailer;

$main = require __DIR__ . '/main.php';
$params = require __DIR__ . '/params.php';

return ArrayHelper::merge($main, [
    'id'       => 'basic-tests',
    'components' => [
        'db' => require __DIR__ . '/test_db.php',

        'mailer' => [
            'class'          => Mailer::class,
            'viewPath'       => '@app/mail',
            'useFileTransport' => true,
            'messageClass'   => 'yii\symfonymailer\Message',
        ],
        'assetManager' => [
            'basePath' => __DIR__ . '/../web/assets',
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'user' => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            'authTimeout'     => 3600 * 24 * 30,
        ],
        'request' => [
            'cookieValidationKey' => 'test',
            'enableCsrfValidation' => false,
        ],
    ],

    'params' => $params,
]);
