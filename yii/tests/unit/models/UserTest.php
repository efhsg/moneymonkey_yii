<?php

namespace tests\unit\models;

use Yii;
use app\models\User;
use tests\fixtures\UserFixture;

class UserTest extends \Codeception\Test\Unit
{

    private $userService;

    protected function _before()
    {
        $this->userService = Yii::$container->get(\app\services\UserService::class);
    }

    public function _fixtures()
    {
        return [
            'users' => UserFixture::class,
        ];
    }

    public function testFindUserById()
    {
        verify($user = User::findIdentity(100))->notEmpty();
        verify($user->username)->equals('admin');

        verify(User::findIdentity(999))->empty();
    }

    public function testFindUserByAccessToken()
    {
        verify($user = User::findIdentityByAccessToken('100_access_token'))->notEmpty();
        verify($user->username)->equals('admin');

        verify(User::findIdentityByAccessToken('non-existing'))->empty();
    }

    public function testFindUserByUsername()
    {
        verify($user = User::findByUsername('admin'))->notEmpty();
        verify($user->email)->equals('admin@example.com');

        verify(User::findByUsername('not-admin'))->empty();
    }

    public function testFindByPasswordResetToken()
    {

        $user = $this->userService->create('testuser', 'testuser@example.com', 'password123');

        $this->userService->generatePasswordResetToken($user);
        $validToken = $user->password_reset_token;

        // 1. Test with a valid token and an existing user
        verify($foundUser = User::findByPasswordResetToken($validToken))->notEmpty();
        verify($foundUser->username)->equals('testuser');

        // 2. Test with a valid token and a non-existing user
        $user->delete();
        verify(User::findByPasswordResetToken($validToken))->empty();

        // 3. Test with a valid token for an existing user, but the token is expired
        $expiredToken = 'ExpiredToken_' . (time() - User::TOKEN_EXPIRATION_SECONDS - 1);
        $user = $this->userService->create('expireduser', 'expireduser@example.com', 'password123');
        $user->password_reset_token = $expiredToken;
        $user->save(false);

        verify(User::findByPasswordResetToken($expiredToken))->empty();

        // 4. Test with an invalid token format
        verify(User::findByPasswordResetToken('InvalidTokenFormat'))->empty();
    }

    /**
     * @depends testFindUserByUsername
     */
    public function testValidateUser()
    {
        $user = User::find()->active()->byUsername('admin')->one();
        verify($user->validateAuthKey('test100key'))->notEmpty();
        verify($user->validateAuthKey('test102key'))->empty();

        verify($user->validatePassword('admin'))->notEmpty();
        verify($user->validatePassword('123456'))->empty();
    }

    public function testSetPassword()
    {
        $user = new User();
        $password = 'new_secure_password';
        $user->setPassword($password);

        verify($user->password_hash)->notEmpty();
        verify(Yii::$app->security->validatePassword($password, $user->password_hash))->true();
        verify(Yii::$app->security->validatePassword('wrong_password', $user->password_hash))->false();
    }

    public function testGenerateAuthKey()
    {
        $user = new User();
        $user->generateAuthKey();

        verify($user->auth_key)->notEmpty();
        verify(strlen($user->auth_key))->equals(32);
    }

}
