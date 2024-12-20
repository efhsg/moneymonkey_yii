<?php

use tests\fixtures\UserFixture;

class LoginFormCest
{
    private const TEST_USER_ID = 100;

    public function _before(\FunctionalTester $I)
    {
        $I->haveFixtures(['user' => UserFixture::class]);
        $I->amOnRoute('login/login');
    }

    public function openLoginPage(\FunctionalTester $I)
    {
        $I->see('Login', 'h1');
    }

    public function internalLoginById(\FunctionalTester $I)
    {
        $I->amLoggedInAs(self::TEST_USER_ID);
        $I->amOnPage('/');
        $I->see('Logout (admin)');
    }

    public function internalLoginByInstance(\FunctionalTester $I)
    {
        $user = \app\models\User::findByUsername('admin');
        if ($user === null) {
            $I->fail("User with username 'admin' does not exist.");
        } else {
            $I->amLoggedInAs($user);
            $I->amOnPage('/');
            $I->see('Logout (admin)');
        }
    }

    private function seeValidationErrors(\FunctionalTester $I, array $errors)
    {
        foreach ($errors as $error) {
            $I->see($error);
        }
    }

    public function loginWithEmptyCredentials(\FunctionalTester $I)
    {
        $I->seeElement('#login-form');
        $I->submitForm('#login-form', []);
        $I->expectTo('see validations errors');
        $this->seeValidationErrors($I, [
            'Username cannot be blank.',
            'Password cannot be blank.'
        ]);
    }

    public function loginWithWrongCredentials(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'wrong',
        ]);
        $I->expectTo('see validations errors');
        $this->seeValidationErrors($I, ['Incorrect username or password.']);
    }

    public function loginSuccessfully(\FunctionalTester $I)
    {
        $I->submitForm('#login-form', [
            'LoginForm[username]' => 'admin',
            'LoginForm[password]' => 'admin',
        ]);
        $I->see('Logout (admin)');
        $I->dontSeeElement('form#login-form');
        $I->seeElement('button.logout');
    }
}
