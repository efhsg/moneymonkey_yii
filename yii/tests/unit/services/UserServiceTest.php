<?php

namespace tests\unit\services;

use app\modules\config\models\Industry;
use app\modules\config\models\MetricType;
use app\modules\config\models\Sector;
use app\modules\identity\models\User;
use app\modules\identity\services\UserService;
use Codeception\Test\Unit;
use PHPUnit\Framework\MockObject\Exception;
use tests\fixtures\UserFixture;
use Yii;

class UserServiceTest extends Unit
{
    private UserService $userService;

    public function _fixtures(): array
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

    public function testCreateUserSeedsSectorsAndIndustries()
    {
        $username = 'seederuser';
        $email = 'seederuser@example.com';
        $password = 'securepassword123';

        $user = $this->userService->create($username, $email, $password);
        $this->assertNotNull($user);

        $sectors = Sector::find()->where(['user_id' => $user->id])->all();
        $this->assertNotEmpty($sectors);

        $sectorIds = Sector::find()->select('id')->where(['user_id' => $user->id])->column();
        $industries = Industry::find()->where(['sector_id' => $sectorIds])->all();
        $this->assertNotEmpty($industries);
    }

    public function testCreateUserSeedsMetricTypes()
    {
        $username = 'metricuser';
        $email = 'metricuser@example.com';
        $password = 'securepassword123';

        $user = $this->userService->create($username, $email, $password);
        $this->assertNotNull($user);

        $metricTypes = MetricType::find()->where(['user_id' => $user->id])->all();
        $this->assertNotEmpty($metricTypes);
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

    public function testHardDelete()
    {
        $user = User::findOne(100);
        $this->assertNotNull($user);

        $result = $this->userService->hardDelete($user);

        $this->assertTrue($result);
        $deletedUser = User::findOne(100);
        $this->assertNull($deletedUser);
    }

    /**
     * @throws Exception
     */
    public function testHardDeleteFails()
    {
        $user = $this->createMock(User::class);
        $user->method('delete')->willThrowException(new \Exception('Delete operation failed'));

        $result = $this->userService->hardDelete($user);

        $this->assertFalse($result);
    }

    protected function _before(): void
    {
        $this->userService = Yii::$container->get(UserService::class);
    }


}
