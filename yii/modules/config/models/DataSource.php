<?php

namespace app\modules\config\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};
use app\models\Stock;
use app\models\StockData;
use app\models\User;

/**
 * This is the model class for table "data_sources".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $website
 *
 * @property StockData[] $stockData
 * @property User $user
 */
class DataSource extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'data_sources';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'stock_id', 'yield_value'], 'required'],
            [['user_id', 'stock_id'], 'integer'],
            [['yield_value'], 'number'],
            [['date_recorded'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['stock_id'], 'exist', 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
            [['user_id'], 'exist', 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'Associated User',
            'name' => 'Source Name',
            'website' => 'Source Website',
        ];
    }

    public function beforeDelete(): bool
    {
        if (!parent::beforeDelete()) {
            return false;
        }

        if ($this->getStockData()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Gets query for [[StockData]].
     *
     * @return ActiveQuery
     */
    public function getStockData(): ActiveQuery
    {
        return $this->hasMany(StockData::class, ['source_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
