<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "metric_types".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 *
 * @property FinancialMetric[] $financialMetrics
 * @property User $user
 */
class MetricType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'metric_types';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name'], 'required'],
            [['user_id'], 'integer'],
            [['name'], 'string', 'max' => 50],
            [['name'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[FinancialMetrics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinancialMetrics()
    {
        return $this->hasMany(FinancialMetric::class, ['metric_type_id' => 'id']);
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
