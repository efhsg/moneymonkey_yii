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
 * @property int $stock_id
 * @property int $metric_type_id
 * @property int $metric_value   The metric value stored as an integer (e.g., value * 100 for 2 decimal places)
 * @property string $date_recorded
 *
 * @property MetricType $metricType
 * @property Stock $stock
 */
class FinancialMetric extends ActiveRecord
{
    /**
     * For 2 decimal places, the factor is 100.
     */
    private const DECIMAL_FACTOR = 100;

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
            [['stock_id', 'metric_type_id', 'metric_value'], 'required'],
            [['stock_id', 'metric_type_id', 'metric_value'], 'integer'],
            ['metric_value', 'integer', 'min' => 0, 'message' => 'Metric value must be a non-negative integer.'],
            [['date_recorded'], 'datetime', 'format' => 'php:Y-m-d H:i:s'],
            [['metric_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => MetricType::class, 'targetAttribute' => ['metric_type_id' => 'id']],
            [['stock_id'], 'exist', 'skipOnError' => true, 'targetClass' => Stock::class, 'targetAttribute' => ['stock_id' => 'id']],
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
            'metric_type_id' => 'Metric Name ID',
            'metric_value' => 'Metric Value (in smallest unit)',
            'date_recorded' => 'Date Recorded',
        ];
    }

    /**
     * Gets query for [[MetricType]].
     *
     * @return ActiveQuery
     */
    public function getMetricType(): ActiveQuery
    {
        return $this->hasOne(MetricType::class, ['id' => 'metric_type_id']);
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
     * Set the metric value in decimal form and convert it to integer for storage.
     *
     * @param float|int $value The metric value in decimal form (e.g. 1234.56)
     */
    public function setMetricValueDecimal(float|int $value): void
    {
        $this->metric_value = (int) round($value * self::DECIMAL_FACTOR);
    }

    /**
     * Get the metric value in its decimal form by converting the stored integer.
     *
     * @return float The metric value in decimal form (e.g. 1234.56)
     */
    public function getMetricValueDecimal(): float
    {
        return $this->metric_value !== null ? $this->metric_value / self::DECIMAL_FACTOR : 0.0;
    }
}
