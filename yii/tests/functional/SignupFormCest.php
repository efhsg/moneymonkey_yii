<?php

use app\models\User;
use app\services\UserService;
use tests\fixtures\UserFixture;

class SignupFormCest
{

    private UserService $userService;

    public function _before(\FunctionalTester $I)
    {
        $this->userService = Yii::$container->get(UserService::class);
        $I->haveFixtures(['user' => UserFixture::class]);
        $I->amOnRoute('login/signup');
    }

    public function openSignupPage(\FunctionalTester $I)
    {
        $I->see('Signup', 'h1');
    }

    private function seeValidationErrors(\FunctionalTester $I, array $errors)
    {
        foreach ($errors as $error) {
            $I->see($error);
        }
    }

    public function signupWithEmptyFields(\FunctionalTester $I)
    {
        $I->seeElement('#signup-form');
        $I->submitForm('#signup-form', []);
        $I->expectTo('see validation errors');
        $this->seeValidationErrors($I, [
            'Username cannot be blank.',
            'Email cannot be blank.',
            'Password cannot be blank.',
        ]);
    }

    public function signupWithInvalidEmail(\FunctionalTester $I)
    {
        $I->submitForm('#signup-form', [
            'SignupForm[username]' => 'newuser',
            'SignupForm[email]' => 'invalid-email',
            'SignupForm[password]' => 'password123',
        ]);
        $I->expectTo('see validation errors');
        $this->seeValidationErrors($I, ['Email is not a valid email address.']);
    }

    public function signupSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#signup-form', [
            'SignupForm[username]' => 'uniqueuser456',
            'SignupForm[email]' => 'uniqueuser456@example.com',
            'SignupForm[password]' => 'securepassword123',
            'SignupForm[captcha]' => 'testme',
        ]);

        $I->seeInDatabase('user', ['username' => 'uniqueuser456', 'email' => 'uniqueuser456@example.com']);
        $I->see('Login', 'h1');
        $I->see('Registration successful! You can now log in.', 'div.alert-success.alert-dismissible');

        $user = User::findOne(['username' => 'uniqueuser456']);
        if ($user) {
            $this->userService->hardDelete($user);
        }

    }


}
