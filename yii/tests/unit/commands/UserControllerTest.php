<?php

namespace tests\unit\commands;

use Yii;
use Exception;
use app\models\User;
use yii\console\ExitCode;
use Codeception\Test\Unit;
use app\services\UserService;
use tests\fixtures\UserFixture;
use app\commands\UserController;

class UserControllerTest extends Unit
{
    /** @var UserController */
    private $controller;

    /** @var UserService */
    private $userService;

    protected function _before()
    {
        $this->userService = Yii::$container->get(UserService::class);

        $this->controller = new class ('user', Yii::$app, $this->userService) extends UserController {
            public function stdout($string)
            {
            }

            public function stderr($string)
            {
            }
        };
    }

    public function _fixtures()
    {
        return [
            'users' => UserFixture::class,
        ];
    }

    public function testActionCreateSuccess()
    {
        $username = 'newuser';
        $email = 'newuser@example.com';
        $password = 'securepassword123';

        $exitCode = $this->controller->actionCreate($username, $email, $password);

        $this->assertEquals(ExitCode::OK, $exitCode);

        $user = User::findOne(['username' => $username]);
        $this->assertNotNull($user);
        $this->assertEquals($email, $user->email);
    }

    public function testActionCreateWithValidationErrors()
    {
        $username = '';
        $email = 'invalid-email';
        $password = 'short';

        $exitCode = $this->controller->actionCreate($username, $email, $password);

        $this->assertEquals(ExitCode::UNSPECIFIED_ERROR, $exitCode);
    }

    public function testActionCreateWithException(): void
    {
        /** @var UserService|\PHPUnit\Framework\MockObject\MockObject $mockUserService */
        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->method('create')->willThrowException(new Exception('Database error'));

        /** @var UserController $controller */
        $controller = new class ('user', Yii::$app, $mockUserService) extends UserController {
            public function stdout($string)
            {
            }

            public function stderr($string)
            {
            }
        };

        $exitCode = $controller->actionCreate('testuser', 'test@example.com', 'password123');
        $this->assertEquals(ExitCode::UNSPECIFIED_ERROR, $exitCode);
    }

}
