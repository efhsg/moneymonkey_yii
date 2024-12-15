<?php

use yii\db\Migration;

class m241213_232008_create_config_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('data_sources', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull()->unique(),
            'website' => $this->string(255),
        ]);
        $this->addForeignKey('fk-data_sources-user_id', 'data_sources', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('metric_types', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(50)->notNull()->unique(),
        ]);
        $this->addForeignKey('fk-metric_types-user_id', 'metric_types', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('sectors', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull()->unique(),
        ]);
        $this->addForeignKey('fk-sectors-user_id', 'sectors', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('industries', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string(100)->notNull()->unique(),
            'sector_id' => $this->integer()->notNull(),
        ]);
        $this->addForeignKey('fk-industries-sector_id', 'industries', 'sector_id', 'sectors', 'id', 'CASCADE');
        $this->addForeignKey('fk-industries-user_id', 'industries', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('stocks', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'ticker' => $this->string(10)->notNull()->unique(),
            'company_name' => $this->string(255)->notNull(),
            'industry_id' => $this->integer()->notNull(),
            'market_cap' => $this->decimal(20, 2),
            'price' => $this->decimal(15, 4)->notNull()->check('price >= 0'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey('fk-stocks-industry_id', 'stocks', 'industry_id', 'industries', 'id', 'CASCADE');
        $this->addForeignKey('fk-stocks-user_id', 'stocks', 'user_id', 'user', 'id', 'CASCADE');
        $this->createIndex('idx-stocks-ticker', 'stocks', 'ticker');
        $this->createIndex('idx-stocks-created_at', 'stocks', 'created_at');
        $this->createIndex('idx-stocks-updated_at', 'stocks', 'updated_at');

        $this->createTable('dividend_yields', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'stock_id' => $this->integer()->notNull(),
            'yield_value' => $this->decimal(10, 4)->notNull()->check('yield_value >= 0'),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey('fk-dividend_yields-stock_id', 'dividend_yields', 'stock_id', 'stocks', 'id', 'CASCADE');
        $this->addForeignKey('fk-dividend_yields-user_id', 'dividend_yields', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('financial_metrics', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'stock_id' => $this->integer()->notNull(),
            'metric_type_id' => $this->integer()->notNull(),
            'metric_value' => $this->decimal(15, 2)->notNull()->check('metric_value >= 0'),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey('fk-financial_metrics-stock_id', 'financial_metrics', 'stock_id', 'stocks', 'id', 'CASCADE');
        $this->addForeignKey('fk-financial_metrics-metric_type_id', 'financial_metrics', 'metric_type_id', 'metric_types', 'id', 'CASCADE');
        $this->addForeignKey('fk-financial_metrics-user_id', 'financial_metrics', 'user_id', 'user', 'id', 'CASCADE');

        $this->createTable('stock_data', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'stock_id' => $this->integer()->notNull(),
            'source_id' => $this->integer()->notNull(),
            'date_recorded' => $this->dateTime()->notNull(),
            'data' => $this->json()->notNull(),
        ]);
        $this->addForeignKey('fk-stock_data-stock_id', 'stock_data', 'stock_id', 'stocks', 'id', 'CASCADE');
        $this->addForeignKey('fk-stock_data-source_id', 'stock_data', 'source_id', 'data_sources', 'id', 'CASCADE');
        $this->addForeignKey('fk-stock_data-user_id', 'stock_data', 'user_id', 'user', 'id', 'CASCADE');
        $this->createIndex('idx-stock_data-stock_id-source_id-date_recorded', 'stock_data', ['stock_id', 'source_id', 'date_recorded']);

        $this->createTable('stock_price_history', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'stock_id' => $this->integer()->notNull(),
            'price' => $this->decimal(15, 4)->notNull(),
            'date_recorded' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->notNull(),
        ]);
        $this->addForeignKey('fk-stock_price_history-stock_id', 'stock_price_history', 'stock_id', 'stocks', 'id', 'CASCADE');
        $this->addForeignKey('fk-stock_price_history-user_id', 'stock_price_history', 'user_id', 'user', 'id', 'CASCADE');
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
        $this->dropTable('metric_types');
        $this->dropTable('data_sources');
    }
}
