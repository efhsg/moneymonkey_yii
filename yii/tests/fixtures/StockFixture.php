<?php

namespace tests\fixtures;

use yii\test\ActiveFixture;

class StockFixture extends ActiveFixture
{
    public $modelClass = 'app\models\Stock';
    public $dataFile = '@tests/fixtures/data/stock.php';
}
