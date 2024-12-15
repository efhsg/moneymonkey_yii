<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "financial_metrics".
 *
 * @property int $id
 * @property int $user_id
 * @property int $stock_id
 * @property int $metric_type_id
 * @property float $metric_value
 * @property string $date_recorded
 *
 * @property MetricType $metricType
 * @property Stock $stock
 * @property User $user
 */
class FinancialMetric extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'financial_metrics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'stock_id', 'metric_type_id', 'metric_value'], 'required'],
            [['user_id', 'stock_id', 'metric_type_id'], 'integer'],
            [['metric_value'], 'number'],
            [['date_recorded'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['metric_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetricType::class, 'targetAttribute' => ['metric_type_id' => 'id']],
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
            'metric_type_id' => 'Metric Name ID',
            'metric_value' => 'Metric Value',
            'date_recorded' => 'Date Recorded',
        ];
    }

    /**
     * Gets query for [[MetricType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMetricType(): ActiveQuery
    {
        return $this->hasOne(MetricType::class, ['id' => 'metric_type_id']);
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
