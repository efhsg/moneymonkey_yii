<?php /** @noinspection PhpUnused */

namespace tests\fixtures;

class StockDependentFixture extends SectorFixture
{
    public $depends = [
        SectorFixture::class,
        IndustryFixture::class,
    ];
}