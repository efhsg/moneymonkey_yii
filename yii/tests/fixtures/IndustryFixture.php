<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class IndustryFixture extends ActiveFixture
{
    public $modelClass = 'app\modules\config\models\Industry';
    public $dataFile = '@tests/fixtures/data/industry.php';
}