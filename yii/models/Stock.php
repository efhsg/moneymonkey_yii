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
 * @property string $ticker
 * @property string $company_name
 * @property int $industry_id
 * @property int|null $market_cap   Stored as integer (market cap * 100)
 * @property int $price             Stored as integer (price * 10000)
 * @property string $created_at
 * @property string $updated_at
 *
 * @property DividendYield[] $dividendYields
 * @property FinancialMetric[] $financialMetrics
 * @property Industry $industry
 * @property StockData[] $stockData
 * @property StockPriceHistory[] $stockPriceHistories
 */
class Stock extends ActiveRecord
{
    private const MARKET_CAP_FACTOR = 100;
    private const PRICE_FACTOR = 100;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'stocks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['ticker', 'company_name', 'industry_id', 'price'], 'required'],
            [['industry_id', 'market_cap', 'price'], 'integer'],
            ['market_cap', 'integer', 'min' => 0, 'message' => 'Market Cap must be a non-negative integer.'],
            ['price', 'integer', 'min' => 0, 'message' => 'Price must be a non-negative integer.'],
            [['created_at', 'updated_at'], 'safe'],
            [['ticker'], 'string', 'max' => 10],
            [['company_name'], 'string', 'max' => 255],
            [
                ['industry_id', 'ticker'],
                'unique',
                'targetAttribute' => ['industry_id', 'ticker'],
                'message' => 'The combination of Industry and Ticker has already been taken.'
            ],
            [
                ['industry_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => Industry::class,
                'targetAttribute' => ['industry_id' => 'id']
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
            'ticker' => 'Ticker',
            'company_name' => 'Company Name',
            'industry_id' => 'Industry ID',
            'market_cap' => 'Market Cap (in smallest unit)',
            'price' => 'Price (in smallest unit)',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[DividendYields]].
     *
     * @return ActiveQuery
     */
    public function getDividendYields(): ActiveQuery
    {
        return $this->hasMany(DividendYield::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[FinancialMetrics]].
     *
     * @return ActiveQuery
     */
    public function getFinancialMetrics(): ActiveQuery
    {
        return $this->hasMany(FinancialMetric::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[Industry]].
     *
     * @return ActiveQuery
     */
    public function getIndustry(): ActiveQuery
    {
        return $this->hasOne(Industry::class, ['id' => 'industry_id']);
    }

    /**
     * Gets query for [[StockData]].
     *
     * @return ActiveQuery
     */
    public function getStockData(): ActiveQuery
    {
        return $this->hasMany(StockData::class, ['stock_id' => 'id']);
    }

    /**
     * Gets query for [[StockPriceHistories]].
     *
     * @return ActiveQuery
     */
    public function getStockPriceHistories(): ActiveQuery
    {
        return $this->hasMany(StockPriceHistory::class, ['stock_id' => 'id']);
    }

    /**
     * Set market cap in decimal form and convert it to integer for storage.
     *
     * @param float|int|null $value
     */
    public function setMarketCapDecimal(float|int|null $value): void
    {
        $this->market_cap = $value !== null ? (int) round($value * self::MARKET_CAP_FACTOR) : null;
    }

    /**
     * Get the market cap in decimal form.
     *
     * @return float|null
     */
    public function getMarketCapDecimal(): ?float
    {
        return $this->market_cap !== null ? $this->market_cap / self::MARKET_CAP_FACTOR : null;
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
