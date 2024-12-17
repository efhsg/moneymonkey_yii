<?php

namespace tests\unit\models;

use Yii;
use app\models\User;
use Codeception\Stub;
use app\models\SignupForm;
use app\services\UserService;

class SignupFormTest extends \Codeception\Test\Unit
{

    public function testSignupSuccess()
    {
        /** @var UserService $userService */
        $userService = Stub::make(UserService::class, [
            'create' => function ($username, $email, $password) {
                $user = new User([
                    'username' => $username,
                    'email' => $email,
                ]);
                $user->setPassword($password);
                return $user;
            }
        ]);

        $model = new SignupForm($userService, [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'securepassword',
        ]);

        $user = $model->signup();

        verify($user)->notEmpty();
        verify($user->username)->equals('newuser');
        verify($user->email)->equals('newuser@example.com');
        verify($user->validatePassword('securepassword'))->true();
    }

    public function testSignupFailureWithEmptyFields()
    {
        /** @var UserService $userService */
        $userService = Stub::make(UserService::class);

        $model = new SignupForm($userService, [
            'username' => '',
            'email' => '',
            'password' => '',
        ]);

        $user = $model->signup();

        verify($user)->null();
        verify($model->hasErrors('username'))->true();
        verify($model->hasErrors('email'))->true();
        verify($model->hasErrors('password'))->true();
    }

    public function testSignupFailureWithInvalidPasswordLength()
    {
        $userService = Stub::make(UserService::class);

        /** @var UserService $userService */
        $model = new SignupForm($userService, [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'ab', // Too short
        ]);

        $user = $model->signup();

        verify($user)->null();
        verify($model->hasErrors('password'))->true();
    }

    public function testSignupFailureWhenUserServiceThrowsException()
    {
        $userService = Stub::make(UserService::class, [
            'create' => function () {
                throw new \app\exceptions\UserCreationException('User creation failed');
            }
        ]);

        /** @var UserService $userService */
        $model = new SignupForm($userService, [
            'username' => 'newuser',
            'email' => 'newuser@example.com',
            'password' => 'securepassword',
        ]);

        $user = $model->signup();

        verify($user)->null();
        verify($model->hasErrors('username'))->true();
        verify($model->getFirstError('username'))->equals('User creation failed');
    }



}
