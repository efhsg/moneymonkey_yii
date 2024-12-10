<?php

namespace tests\unit\services;

use Yii;
use app\models\User;
use app\services\UserService;
use tests\fixtures\UserFixture;
use Codeception\Test\Unit;

class UserServiceTest extends Unit
{
    private UserService $userService;

    protected function _before()
    {
        $this->userService = Yii::$container->get(UserService::class);
    }

    public function _fixtures()
    {
        return [
            'users' => UserFixture::class,
        ];
    }

    public function testCreateUser()
    {
        $username = 'newuser';
        $email = 'newuser@example.com';
        $password = 'securepassword123';

        $user = $this->userService->create($username, $email, $password);

        verify($user)->notEmpty();
        verify($user->username)->equals($username);
        verify($user->email)->equals($email);
        verify(Yii::$app->security->validatePassword($password, $user->password_hash))->true();
        verify($user->status)->equals(User::STATUS_ACTIVE);
    }

    public function testCreateUserWithException()
    {
        $this->expectException(\Exception::class);

        $this->userService->create('invaliduser', 'invalid-email', 'password123');
    }

    public function testGeneratePasswordResetToken()
    {
        $user = User::findOne(100);

        $result = $this->userService->generatePasswordResetToken($user);

        verify($result)->true();
        verify($user->password_reset_token)->notEmpty();
        verify(strpos($user->password_reset_token, '_'))->notFalse();
    }

    public function testRemovePasswordResetToken()
    {
        $user = User::findOne(100);
        $this->userService->generatePasswordResetToken($user);

        verify($user->password_reset_token)->notEmpty();

        $result = $this->userService->removePasswordResetToken($user);

        verify($result)->true();
        verify($user->password_reset_token)->null();
    }

    public function testSoftDelete()
    {
        $user = User::findOne(100);
        verify($user->deleted_at)->null();

        $result = $this->userService->softDelete($user);

        verify($result)->true();
        verify($user->deleted_at)->notNull();
    }

    public function testSoftDeleteAlreadyDeleted()
    {
        $user = User::findOne(100);
        $this->userService->softDelete($user);

        $result = $this->userService->softDelete($user);

        verify($result)->false();
    }

    public function testRestoreSoftDelete()
    {
        $user = User::findOne(100);
        $this->userService->softDelete($user);

        verify($user->deleted_at)->notNull();

        $result = $this->userService->restoreSoftDelete($user);

        verify($result)->true();
        verify($user->deleted_at)->null();
    }

    public function testRestoreSoftDeleteNotDeleted()
    {
        $user = User::findOne(100);
        verify($user->deleted_at)->null();

        $result = $this->userService->restoreSoftDelete($user);

        verify($result)->false();
    }
}
