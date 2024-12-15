<?php

namespace app\models;

use yii\db\{
    ActiveQuery,
    ActiveRecord
};

/**
 * This is the model class for table "stocks".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ticker
 * @property string $company_name
 * @property int $industry_id
 * @property float|null $market_cap
 * @property float $price
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DividendYield[] $dividendYields
 * @property FinancialMetric[] $financialMetrics
 * @property Industry $industry
 * @property StockData[] $stockDatas
 * @property StockPriceHistory[] $stockPriceHistories
 * @property User $user
 */
class Stock extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'ticker', 'company_name', 'industry_id', 'price'], 'required'],
            [['user_id', 'industry_id'], 'integer'],
            [['market_cap', 'price'], 'number'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticker'], 'string', 'max' => 10],
            [['company_name'], 'string', 'max' => 255],
            [['ticker'], 'unique'],
            [['industry_id'], 'exist', 'skipOnError' => true, 'targetClass' => Industry::class, 'targetAttribute' => ['industry_id' => 'id']],
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
            'ticker' => 'Ticker',
            'company_name' => 'Company Name',
            'industry_id' => 'Industry ID',
            'market_cap' => 'Market Cap',
            'price' => 'Price',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[DividendYields]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDividendYields(): ActiveQuery
    {
        return $this->hasMany(DividendYield::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[FinancialMetrics]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFinancialMetrics(): ActiveQuery
    {
        return $this->hasMany(FinancialMetric::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[Industry]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndustry(): ActiveQuery
    {
        return $this->hasOne(Industry::class, ['id' => 'industry_id']);
    }

    /**
     * Gets query for [[StockDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockDatas(): ActiveQuery
    {
        return $this->hasMany(StockData::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[StockPriceHistories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStockPriceHistories(): ActiveQuery
    {
        return $this->hasMany(StockPriceHistory::class, ['stock_id' => 'id']);
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
