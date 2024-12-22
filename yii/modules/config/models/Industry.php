<?php

namespace app\modules\config\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};
use app\models\Stock;

/**
 * This is the model class for table "industries".
 *
 * @property int $id
 * @property string $name
 * @property int $sector_id
 *
 * @property Sector $sector
 * @property Stock[] $stocks
 */
class Industry extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'industries';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'sector_id'], 'required'],
            [['sector_id'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [
                ['sector_id', 'name'],
                'unique',
                'targetAttribute' => ['sector_id', 'name'],
                'message' => 'The industry name has already been taken in this sector.'
            ],
            [
                ['sector_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Sector::class,
                'targetAttribute' => ['sector_id' => 'id']
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'sector_id' => 'Sector ID',
        ];
    }

    /**
     * Gets query for [[Sector]].
     *
     * @return ActiveQuery
     */
    public function getSector(): ActiveQuery
    {
        return $this->hasOne(Sector::class, ['id' => 'sector_id']);
    }

    /**
     * Gets query for [[Stocks]].
     *
     * @return ActiveQuery
     */
    public function getStocks(): ActiveQuery
    {
        return $this->hasMany(Stock::class, ['industry_id' => 'id']);
    }
}
