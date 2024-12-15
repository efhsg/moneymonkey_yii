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
 * @property int $user_id
 * @property int $stock_id
 * @property float $yield_value
 * @property string $date_recorded
 *
 * @property Stock $stock
 * @property User $user
 */
class DividendYield extends ActiveRecord
{
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
            [['user_id', 'stock_id', 'yield_value'], 'required'],
            [['user_id', 'stock_id'], 'integer'],
            [['yield_value'], 'number'],
            [['date_recorded'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'stock_id' => 'Stock ID',
            'yield_value' => 'Yield Value',
            'date_recorded' => 'Date Recorded',
        ];
    }

    /**
     * Gets query for [[Stock]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStock(): ActiveQuery
    {
        return $this->hasOne(Stock::class, ['id' => 'stock_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
