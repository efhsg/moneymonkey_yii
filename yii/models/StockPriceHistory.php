<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "stock_price_history".
 *
 * @property int $id
 * @property int $stock_id
 * @property int $price
 * @property string $date_recorded
 *
 * @property Stock $stock
 */
class StockPriceHistory extends ActiveRecord
{
    private const PRICE_FACTOR = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'stock_price_history';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['stock_id', 'price'], 'required'],
            [['stock_id', 'price'], 'integer'],
            ['price', 'integer', 'min' => 0, 'message' => 'Price must be a non-negative integer.'],
            [['date_recorded'], 'date', 'format' => 'php:Y-m-d H:i:s'],
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
            'price' => 'Price (in smallest unit)',
            'date_recorded' => 'Date Recorded',
        ];
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return ActiveQuery
     */
    public function getStock(): ActiveQuery
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * Set the price in decimal form and convert it to integer for storage.
     *
     * @param float|int $value
     */
    public function setPriceDecimal(float|int $value): void
    {
        $this->price = (int) round($value * self::PRICE_FACTOR);
    }

    /**
     * Get the price in decimal form.
     *
     * @return float
     */
    public function getPriceDecimal(): float
    {
        return $this->price !== null ? $this->price / self::PRICE_FACTOR : 0.0;
    }
}
