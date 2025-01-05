<?php /** @noinspection PhpUnused */

namespace tests\functional\config\controllers;

use Codeception\Exception\ModuleException;
use FunctionalTester;
use tests\fixtures\SectorFixture;
use tests\fixtures\UserFixture;


class SectorControllerCest
{

    /**
     * @throws ModuleException
     */
    public function _before(FunctionalTester $I): void
    {
        $I->haveFixtures(['user' => UserFixture::class, 'sectors' => SectorFixture::class,]);
        $I->amLoggedInAs(100);
    }

    public function testIndexAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/sector/');
        $I->seeResponseCodeIs(200);
    }

    public function testViewSector(FunctionalTester $I): void
    {
        $sectorId = 1;
        $I->amOnRoute('/config/sector/view', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->see('Sector Details');
        $I->seeInSource('<title>');
    }

    public function testUpdateSector(FunctionalTester $I): void
    {
        $sectorId = 1;
        $I->amOnRoute('/config/sector/view', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->seeInDatabase('sectors', ['id' => $sectorId, 'name' => 'Technology']);
        $I->see('Technology');
        $I->amOnRoute('/config/sector/update', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->submitForm('#sector-form', [
            'Sector[name]' => 'Updated Sector Name',
        ]);
        $I->seeInDatabase('sectors', ['id' => $sectorId, 'name' => 'Updated Sector Name']);
        $I->amOnRoute('/config/sector/view', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->see('Updated Sector Name');
    }


    public function testCreateSector(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/sector/create');
        $I->submitForm('#sector-form', [
            'Sector[name]' => 'New Sector',
        ]);
        $I->seeInDatabase('sectors', ['name' => 'New Sector', 'user_id' => 100]);
    }

    public function testDeleteSector(FunctionalTester $I): void
    {
        $sectorId = 1;

        $I->amOnRoute('/config/sector/view', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->seeInDatabase('sectors', ['id' => $sectorId]);

        $I->amOnRoute('/config/sector/delete-confirm', ['id' => $sectorId]);
        $I->seeResponseCodeIs(200);
        $I->see('Confirm Delete Sector');
        $I->seeElement('#delete-confirmation-form');

        $I->submitForm('#delete-confirmation-form', [
            'confirm' => '1',
        ]);

        $I->dontSeeInDatabase('sectors', ['id' => $sectorId]);
        $I->amOnRoute('/config/sector/index');
        $I->seeResponseCodeIs(200);
    }

    public function testUnauthorizedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/sector/');
        $I->seeResponseCodeIs(200);
        $I->amOnRoute('/identity/auth/logout');
        $I->seeResponseCodeIs(200);
        $I->amOnRoute('/config/sector/');
        $I->seeResponseCodeIs(200);
        $I->see('Login', 'h1');
    }

}
