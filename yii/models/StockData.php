<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "stock_data".
 *
 * @property int $id
 * @property int $user_id
 * @property int $stock_id
 * @property int $source_id
 * @property string $date_recorded
 * @property string $data
 *
 * @property DataSource $source
 * @property Stock $stock
 * @property User $user
 */
class StockData extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'stock_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'stock_id', 'source_id', 'date_recorded', 'data'], 'required'],
            [['user_id', 'stock_id', 'source_id'], 'integer'],
            [['date_recorded'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => DataSource::class, 'targetAttribute' => ['source_id' => 'id']],
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
            'source_id' => 'Source ID',
            'date_recorded' => 'Date Recorded',
            'data' => 'Data',
        ];
    }

    /**
     * Gets query for [[Source]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource(): ActiveQuery
    {
        return $this->hasOne(DataSource::class, ['id' => 'source_id']);
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
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
