<?php

use yii\db\Migration;

/**
 * Class m241202_144139_seed_initial_data
 */
class m241202_144139_seed_initial_data extends Migration
{
    public function safeUp()
    {
        // Data to be inserted
        $sectorsData = [
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

        $metricNames = [
            'PE Ratio',
            'EPS',
            'Debt to Equity',
            'Current Ratio',
            'Return on Equity',
            'Return on Assets',
        ];

        // Insert sectors and industries
        foreach ($sectorsData as $sectorName => $industries) {
            $this->insert('{{%sectors}}', ['name' => $sectorName]);
            $sectorId = $this->db->getLastInsertID();

            foreach ($industries as $industryName) {
                $this->insert('{{%industries}}', [
                    'name' => $industryName,
                    'sector_id' => $sectorId,
                ]);
            }
        }

        // Insert metric names
        foreach ($metricNames as $metricName) {
            $this->insert('{{%metric_names}}', ['name' => $metricName]);
        }
    }

    public function safeDown()
    {
        // Delete metric names
        $this->delete('{{%metric_name}}');

        // Delete industries
        $this->delete('{{%industry}}');

        // Delete sectors
        $this->delete('{{%sector}}');
    }

}
