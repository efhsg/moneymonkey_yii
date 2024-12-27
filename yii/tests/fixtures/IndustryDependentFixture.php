<?php

namespace tests\fixtures;


class IndustryDependentFixture extends SectorFixture
{
    public $depends = [
        SectorFixture::class,
    ];
}