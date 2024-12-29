<?php

namespace fixtures;


use tests\fixtures\IndustryFixture;
use tests\fixtures\SectorFixture;

class StockDependentFixture extends SectorFixture
{
    public $depends = [
        SectorFixture::class,
        IndustryFixture::class,
    ];
}