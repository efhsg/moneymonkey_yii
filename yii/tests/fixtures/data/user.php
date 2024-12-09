<?php

return [
    'user1' => [
        'id' => 100,
        'username' => 'admin',
        'auth_key' => 'test100key',
        'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
        'access_token' => '100_access_token',
        'email' => 'admin@example.com',
        'password_reset_token' => 'FJvf6GvnNSjrGrE8Oxtgu9PKYe_HXFDm_1733759891',
        'created_at' => time(),
        'updated_at' => time(),
    ],
];
