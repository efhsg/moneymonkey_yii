<?php /** @noinspection PhpUnused */

namespace tests\functional\config\controllers;

use Codeception\Exception\ModuleException;
use FunctionalTester;
use tests\fixtures\IndustryFixture;
use tests\fixtures\SectorFixture;
use tests\fixtures\StockFixture;
use tests\fixtures\UserFixture;

class IndustryControllerCest
{
    /**
     * @throws ModuleException
     */
    public function _before(FunctionalTester $I): void
    {
        $I->haveFixtures([
            'user' => UserFixture::class,
            'sectors' => SectorFixture::class,
            'industries' => IndustryFixture::class,
            'stocks' => StockFixture::class,
        ]);
        $I->amLoggedInAs(100);
    }

    public function testIndexAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/industry/');
        $I->seeResponseCodeIs(200);
        $I->see('Industries');
        $I->see('Software Development');
    }

    public function testViewIndustry(FunctionalTester $I): void
    {
        $industryId = 1;
        $I->amOnRoute('/config/industry/view', ['id' => $industryId]);
        $I->seeResponseCodeIs(200);
        $I->see('Industry Details');
        $I->see('AAPL');
        $I->see('Apple Inc.');

        $this->assertStockBelongsToIndustry($I, $industryId);
    }

    private function assertStockBelongsToIndustry(FunctionalTester $I, int $industryId): void
    {
        $expectedStocks = [
            ['ticker' => 'AAPL', 'company_name' => 'Apple Inc.'],
        ];
        foreach ($expectedStocks as $stock) {
            $I->seeInDatabase('stocks', [
                'ticker' => $stock['ticker'],
                'company_name' => $stock['company_name'],
                'industry_id' => $industryId,
            ]);
        }
    }

    public function testCreateIndustry(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/industry/create');
        $I->submitForm('#industry-form', [
            'Industry[name]' => 'New Industry',
            'Industry[sector_id]' => 1,
        ]);
        $I->seeInDatabase('industries', ['name' => 'New Industry', 'sector_id' => 1]);
    }

    public function testUpdateIndustry(FunctionalTester $I): void
    {
        $industryId = 1;
        $I->amOnRoute('/config/industry/update', ['id' => $industryId]);
        $I->submitForm('#industry-form', [
            'Industry[name]' => 'Updated Industry Name',
        ]);
        $I->seeInDatabase('industries', ['id' => $industryId, 'name' => 'Updated Industry Name']);
    }

    public function testDeleteIndustry(FunctionalTester $I): void
    {
        $industryId = 1;
        $I->amOnRoute('/config/industry/delete-confirm', ['id' => $industryId]);
        $I->submitForm('#delete-confirmation-form', ['confirm' => '1']);
        $I->dontSeeInDatabase('industries', ['id' => $industryId]);
    }

    public function testStocksBelongToIndustries(FunctionalTester $I): void
    {
        $industryId = 1;
        $I->amOnRoute('/config/industry/view', ['id' => $industryId]);
        $I->seeResponseCodeIs(200);
        $this->assertStockBelongsToIndustry($I, 1);
    }

    public function testInvalidUpdateIndustry(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/industry/update', ['id' => 999]); // Non-existent industry
        $I->seeResponseCodeIs(404);
    }

    public function testUnauthorizedAccess(FunctionalTester $I): void
    {
        $I->amOnRoute('/config/industry/');
        $I->seeResponseCodeIs(200);
        $I->amOnRoute('/identity/auth/logout');
        $I->seeResponseCodeIs(200);
        $I->amOnRoute('/config/industry/');
        $I->seeResponseCodeIs(200);
        $I->see('Login', 'h1');
    }
}
