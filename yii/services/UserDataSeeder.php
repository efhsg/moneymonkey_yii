<?php /** @noinspection PhpUnused */

namespace app\services;

use app\modules\identity\services\UserDataSeederInterface;
use Yii;
use yii\db\Exception;

class UserDataSeeder implements UserDataSeederInterface
{
    /**
     * Seed user data with sectors, industries, and metric types.
     *
     * @param int $userId The user ID to associate the data with.
     * @throws Exception If any database operation fails.
     */
    public function seed(int $userId): void
    {
        $sectorsData = $this->getSectorsData();
        $metricNames = $this->getMetricNames();

        $this->seedSectorsAndIndustries($sectorsData, $userId);
        $this->seedMetricNames($metricNames, $userId);
    }

    /**
     * Get sectors and their corresponding industries.
     *
     * @return array
     */
    private function getSectorsData(): array
    {
        return [
            'Communication Services' => [
                'Diversified Telecommunication Services',
                'Entertainment',
                'Interactive Media & Services',
                'Media',
                'Wireless Telecommunication Services',
            ],
            'Consumer Discretionary' => [
                'Auto Components',
                'Automobiles',
                'Distributors',
                'Diversified Consumer Services',
                'Hotels, Restaurants & Leisure',
                'Household Durables',
                'Internet & Direct Marketing Retail',
                'Leisure Products',
                'Multiline Retail',
                'Specialty Retail',
                'Textiles, Apparel & Luxury Goods',
            ],
            'Consumer Staples' => [
                'Beverages',
                'Food & Staples Retailing',
                'Food Products',
                'Household Products',
                'Personal Products',
                'Tobacco',
            ],
            'Energy' => [
                'Energy Equipment & Services',
                'Oil, Gas & Consumable Fuels',
            ],
            'Financials' => [
                'Banks',
                'Capital Markets',
                'Consumer Finance',
                'Diversified Financial Services',
                'Insurance',
                'Mortgage Real Estate Investment Trusts (REITs)',
                'Thrifts & Mortgage Finance',
            ],
            'Health Care' => [
                'Biotechnology',
                'Health Care Equipment & Supplies',
                'Health Care Providers & Services',
                'Health Care Technology',
                'Life Sciences Tools & Services',
                'Pharmaceuticals',
            ],
            'Industrials' => [
                'Aerospace & Defense',
                'Air Freight & Logistics',
                'Airlines',
                'Building Products',
                'Commercial Services & Supplies',
                'Construction & Engineering',
                'Electrical Equipment',
                'Industrial Conglomerates',
                'Machinery',
                'Marine',
                'Professional Services',
                'Road & Rail',
                'Trading Companies & Distributors',
                'Transportation Infrastructure',
            ],
            'Information Technology' => [
                'Communications Equipment',
                'Electronic Equipment, Instruments & Components',
                'IT Services',
                'Semiconductors & Semiconductor Equipment',
                'Software',
                'Technology Hardware, Storage & Peripherals',
            ],
            'Materials' => [
                'Chemicals',
                'Construction Materials',
                'Containers & Packaging',
                'Metals & Mining',
                'Paper & Forest Products',
            ],
            'Real Estate' => [
                'Equity Real Estate Investment Trusts (REITs)',
                'Real Estate Management & Development',
            ],
            'Utilities' => [
                'Electric Utilities',
                'Gas Utilities',
                'Independent Power and Renewable Electricity Producers',
                'Multi-Utilities',
                'Water Utilities',
            ],
        ];
    }

    /**
     * Get metric names.
     *
     * @return array
     */
    private function getMetricNames(): array
    {
        return [
            'PE Ratio',
            'EPS',
            'Debt to Equity',
            'Current Ratio',
            'Return on Equity',
            'Return on Assets',
        ];
    }

    /**
     * Seed sectors and industries.
     *
     * @param array $sectorsData
     * @param int $userId
     * @throws Exception
     */
    private function seedSectorsAndIndustries(array $sectorsData, int $userId): void
    {
        foreach ($sectorsData as $sectorName => $industries) {
            Yii::$app->db->createCommand()->insert('{{%sectors}}', [
                'name' => $sectorName,
                'user_id' => $userId,
            ])->execute();

            $sectorId = Yii::$app->db->getLastInsertID();

            foreach ($industries as $industryName) {
                Yii::$app->db->createCommand()->insert('{{%industries}}', [
                    'name' => $industryName,
                    'sector_id' => $sectorId,
                ])->execute();
            }
        }
    }

    /**
     * Seed metric types.
     *
     * @param array $metricNames
     * @param int $userId
     * @throws Exception
     */
    private function seedMetricNames(array $metricNames, int $userId): void
    {
        foreach ($metricNames as $metricName) {
            Yii::$app->db->createCommand()->insert('{{%metric_types}}', [
                'name' => $metricName,
                'user_id' => $userId,
            ])->execute();
        }
    }
}
