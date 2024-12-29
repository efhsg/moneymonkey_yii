<?php

namespace tests\unit\config\models;

use app\models\Stock;
use app\modules\config\models\Industry;
use app\modules\config\models\Sector;
use Codeception\Test\Unit;
use tests\fixtures\IndustryFixture;
use tests\fixtures\SectorFixture;
use tests\fixtures\StockFixture;
use tests\fixtures\UserFixture;

class IndustryTest extends Unit
{
    public function _fixtures(): array
    {
        return [
            'users' => UserFixture::class,
            'sectors' => SectorFixture::class,
            'industries' => IndustryFixture::class,
            'stocks' => StockFixture::class,
        ];
    }

    public function testFindIndustryById()
    {
        $industry = Industry::findOne(1);
        verify($industry)->notEmpty();
        verify($industry->name)->equals('Software Development');

        verify(Industry::findOne(999))->empty();
    }

    public function testFindIndustryByNameAndSectorId()
    {
        $industry = Industry::findOne(['name' => 'Software Development', 'sector_id' => 1]);
        verify($industry)->notEmpty();
        verify($industry->id)->equals(1);

        verify(Industry::findOne(['name' => 'Non-Existing', 'sector_id' => 1]))->empty();
        verify(Industry::findOne(['name' => 'Software', 'sector_id' => 999]))->empty();
    }

    public function testIndustryBelongsToSector()
    {
        $industry = Industry::findOne(1);
        verify($industry)->notEmpty();

        $sector = $industry->sector;
        verify($sector)->notEmpty();
        verify($sector->id)->equals($industry->sector_id);
    }

    public function testValidateIndustry()
    {
        $industry = new Industry();
        $industry->sector_id = 1;
        $industry->name = 'New Industry';
        verify($industry->validate())->true();

        $industry->name = null;
        verify($industry->validate())->false();

        $industry->name = str_repeat('a', 101);
        verify($industry->validate())->false();
    }

    public function testUniqueIndustryNameWithinSector()
    {
        $industry = new Industry();
        $industry->sector_id = 1;
        $industry->name = 'Software Development';
        verify($industry->validate())->false();

        $industry->name = 'Unique Industry';
        verify($industry->validate())->true();
    }

    public function testIndustryHasStocksRelation()
    {
        $industry = Industry::findOne(1);
        verify($industry)->notEmpty();

        $stocks = $industry->stocks;
        verify($stocks)->notEmpty();
        verify(is_array($stocks))->true();
        verify($stocks[0]->industry_id)->equals($industry->id);

        foreach ($stocks as $stock) {
            $this->assertInstanceOf(Stock::class, $stock);
        }
    }

    public function testStocksBelongToIndustry()
    {
        $stocks = Stock::find()->all();
        verify($stocks)->notEmpty();

        /** @var Stock $stock */
        foreach ($stocks as $stock) {
            verify($stock->industry)->notEmpty();
            $this->assertInstanceOf(Industry::class, $stock->industry);
            verify($stock->industry_id)->equals($stock->industry->id);
        }
    }

    public function testNameValidationFailsWhenExceedingMaxLength()
    {
        $industry = new Industry();
        $industry->sector_id = 1;
        $industry->name = str_repeat('a', 101);
        verify($industry->validate())->false();
        verify($industry->getErrors('name'))->notEmpty();
    }

    public function testSectorRelationIsValid()
    {
        $industry = Industry::findOne(1);
        verify($industry)->notEmpty();

        $sector = $industry->sector;
        verify($sector)->notEmpty();
        $this->assertInstanceOf(Sector::class, $sector);
        verify($sector->id)->equals($industry->sector_id);
    }

}
