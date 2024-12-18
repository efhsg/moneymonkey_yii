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

    public function testActionDeleteSoftDeleteSuccess()
    {
        $username = 'admin';
        $user = User::findOne(['username' => $username]);
        $this->assertNotNull($user, 'The user must exist before the test.');

        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->method('softDelete')->willReturnCallback(function ($user) {
            $user->deleted_at = time();
            return $user->save(false);
        });

        $controller = new class ('user', Yii::$app, $mockUserService) extends UserController {
            public function stdout($string)
            {
            }
            public function stderr($string)
            {
            }
            public function prompt($message, $options = [])
            {
                return 'soft';
            }
        };

        $exitCode = $controller->actionDelete($username);
        $this->assertEquals(ExitCode::OK, $exitCode, 'Soft delete should return ExitCode::OK');

        $user->refresh();
        $this->assertNotNull($user->deleted_at, 'The user should be marked as soft deleted.');
    }

    public function testActionDeleteHardDeleteSuccess()
    {
        $username = 'admin';
        $user = User::findOne(['username' => $username]);
        $this->assertNotNull($user, 'The user must exist before the test.');

        $mockUserService = $this->createMock(UserService::class);
        $mockUserService->method('hardDelete')->willReturnCallback(function ($user) {
            return $user->delete() !== false;
        });

        $controller = new class ('user', Yii::$app, $mockUserService) extends UserController {
            public function stdout($string)
            {
            }
            public function stderr($string)
            {
            }
            public function prompt($message, $options = [])
            {
                return 'hard';
            }
        };

        $exitCode = $controller->actionDelete($username);
        $this->assertEquals(ExitCode::OK, $exitCode, 'Hard delete should return ExitCode::OK');

        $deletedUser = User::findOne(['username' => $username]);
        $this->assertNull($deletedUser, 'The user should be fully removed after a hard delete.');
    }


}
