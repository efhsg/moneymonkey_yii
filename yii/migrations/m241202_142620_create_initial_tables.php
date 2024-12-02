<?php

use yii\db\Migration;

/**
 * Class m241202_142620_create_initial_tables
 */
class m241202_142620_create_initial_tables extends Migration
{
    public function safeUp()
    {
        // Create data_sources table
        $this->createTable('data_sources', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'website' => $this->string(255),
        ]);

        // Create metric_names table
        $this->createTable('metric_names', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->unique(),
        ]);

        // Create sectors table
        $this->createTable('sectors', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
        ]);

        // Create industries table
        $this->createTable('industries', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull()->unique(),
            'sector_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-industries-sector_id',
            'industries',
            'sector_id',
            'sectors',
            'id',
            'CASCADE'
        );

        // Create stocks table
        $this->createTable('stocks', [
            'id' => $this->primaryKey(),
            'ticker' => $this->string(10)->notNull()->unique(),
            'company_name' => $this->string(255)->notNull(),
            'industry_id' => $this->integer()->notNull(),
            'market_cap' => $this->decimal(20, 2),
            'price' => $this->decimal(15, 4)->notNull()->check('price >= 0'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey(
            'fk-stocks-industry_id',
            'stocks',
            'industry_id',
            'industries',
            'id',
            'CASCADE'
        );
        $this->createIndex('idx-stocks-ticker', 'stocks', 'ticker');
        $this->createIndex('idx-stocks-created_at', 'stocks', 'created_at');
        $this->createIndex('idx-stocks-updated_at', 'stocks', 'updated_at');

        // Create dividend_yields table
        $this->createTable('dividend_yields', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer()->notNull(),
            'yield_value' => $this->decimal(10, 4)->notNull()->check('yield_value >= 0'),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey(
            'fk-dividend_yields-stock_id',
            'dividend_yields',
            'stock_id',
            'stocks',
            'id',
            'CASCADE'
        );

        // Create financial_metrics table
        $this->createTable('financial_metrics', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer()->notNull(),
            'metric_name_id' => $this->integer()->notNull(),
            'metric_value' => $this->decimal(15, 2)->notNull()->check('metric_value >= 0'),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey(
            'fk-financial_metrics-stock_id',
            'financial_metrics',
            'stock_id',
            'stocks',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-financial_metrics-metric_name_id',
            'financial_metrics',
            'metric_name_id',
            'metric_names',
            'id',
            'CASCADE'
        );

        // Create stock_data table
        $this->createTable('stock_data', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'date_recorded' => $this->dateTime()->notNull(),
            'data' => $this->json()->notNull(),
        ]);
        $this->addForeignKey(
            'fk-stock_data-stock_id',
            'stock_data',
            'stock_id',
            'stocks',
            'id',
            'CASCADE'
        );
        $this->addForeignKey(
            'fk-stock_data-source_id',
            'stock_data',
            'source_id',
            'data_sources',
            'id',
            'CASCADE'
        );
        $this->createIndex(
            'idx-stock_data-stock_id-source_id-date_recorded',
            'stock_data',
            ['stock_id', 'source_id', 'date_recorded']
        );

        // Create stock_price_history table
        $this->createTable('stock_price_history', [
            'id' => $this->primaryKey(),
            'stock_id' => $this->integer()->notNull(),
            'price' => $this->decimal(15, 4)->notNull(),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey(
            'fk-stock_price_history-stock_id',
            'stock_price_history',
            'stock_id',
            'stocks',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('stock_price_history');
        $this->dropTable('stock_data');
        $this->dropTable('financial_metrics');
        $this->dropTable('dividend_yields');
        $this->dropTable('stocks');
        $this->dropTable('industries');
        $this->dropTable('sectors');
        $this->dropTable('metric_names');
        $this->dropTable('data_sources');
    }

}
