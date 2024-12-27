<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class SectorFixture extends ActiveFixture
{
    public $modelClass = 'app\modules\config\models\Sector';
    public $dataFile = '@tests/fixtures/data/sector.php';
}
