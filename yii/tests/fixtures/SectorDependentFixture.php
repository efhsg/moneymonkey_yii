<?php

namespace tests\fixtures;


class SectorDependentFixture extends SectorFixture
{
    public $depends = [
        UserFixture::class,
    ];
}