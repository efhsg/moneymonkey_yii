<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "dividend_yields".
 *
 * @property int $id
 * @property int $stock_id
 * @property int $yield_value  The yield value stored as an integer (e.g., value * 10000 for 4 decimal places)
 * @property string $date_recorded
 *
 * @property Stock $stock
 */
class DividendYield extends ActiveRecord
{
    private const DECIMAL_FACTOR = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'dividend_yields';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['stock_id', 'yield_value'], 'required'],
            [['stock_id', 'yield_value'], 'integer'],
            ['yield_value', 'integer', 'min' => 0, 'message' => 'Yield value must be a non-negative integer.'],
            [['date_recorded'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [
                ['stock_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Stock::class,
                'targetAttribute' => ['stock_id' => 'id']
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
            'stock_id' => 'Stock ID',
            'yield_value' => 'Yield Value (in smallest unit)',
            'date_recorded' => 'Date Recorded',
        ];
    }

    public function getStock(): ActiveQuery
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    public function setYieldValueDecimal(float|int $value): void
    {
        $this->yield_value = (int) round($value * self::DECIMAL_FACTOR);
    }

    public function getYieldValueDecimal(): float
    {
        return $this->yield_value !== null ? $this->yield_value / self::DECIMAL_FACTOR : 0.0;
    }

}
