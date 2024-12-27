<?php

namespace tests\unit\config\models;

use app\modules\config\models\Sector;
use Codeception\Test\Unit;
use tests\fixtures\IndustryFixture;
use tests\fixtures\SectorFixture;
use tests\fixtures\UserFixture;

class SectorTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'users' => UserFixture::class,
            'sectors' => SectorFixture::class,
            'industries' => IndustryFixture::class,
        ];
    }

    public function testFindSectorById()
    {
        $sector = Sector::findOne(1);
        verify($sector)->notEmpty();
        verify($sector->name)->equals('Technology');

        verify(Sector::findOne(999))->empty();
    }

    public function testFindSectorByName()
    {
        $sector = Sector::findOne(['name' => 'Technology']);
        verify($sector)->notEmpty();
        verify($sector->id)->equals(1);

        verify(Sector::findOne(['name' => 'Non-Existing']))->empty();
    }

    public function testSectorBelongsToUser()
    {
        $sector = Sector::findOne(1);
        verify($sector)->notEmpty(); // Ensure the sector exists
        verify($sector->user)->notEmpty(); // Ensure the user relation is not empty
        verify($sector->user->username)->equals('admin');
    }

    public function testValidateSector()
    {
        $sector = new Sector();
        $sector->user_id = 100;
        $sector->name = 'Education';
        verify($sector->validate())->true();

        $sector->name = null;
        verify($sector->validate())->false();
    }

    public function testUniqueSectorName()
    {
        $sector = new Sector();
        $sector->user_id = 100;
        $sector->name = 'Technology';
        verify($sector->validate())->false();

        $sector->name = 'Unique Name';
        verify($sector->validate())->true();
    }

    public function testGetIndustriesReturnsCorrectData()
    {
        $sector = Sector::findOne(1);
        verify($sector)->notEmpty();

        $industries = $sector->industries;
        verify($industries)->notEmpty();

        foreach ($industries as $industry) {
            verify($industry->sector_id)->equals($sector->id);
        }
    }

    public function testGetIndustriesCountReturnsCorrectCount()
    {
        $sector = Sector::findOne(1);
        verify($sector)->notEmpty();

        $industriesCount = $sector->industriesCount;
        $actualCount = $sector->getIndustries()->count();

        verify($industriesCount)->equals($actualCount);
    }

    public function testUserRelationIsValid()
    {
        $sector = Sector::findOne(1);
        verify($sector)->notEmpty();

        $user = $sector->user;
        verify($user)->notEmpty();
        verify($user->id)->equals($sector->user_id);
    }

    public function testNameValidationFailsWhenExceedingMaxLength()
    {
        $sector = new Sector();
        $sector->user_id = 1;
        $sector->name = str_repeat('a', 101); // Exceeding max length of 100
        verify($sector->validate())->false();
        verify($sector->getErrors('name'))->notEmpty();
    }

    public function testTableNameMatchesExpectedValue()
    {
        $tableName = Sector::tableName();
        verify($tableName)->equals('sectors');
    }


}
