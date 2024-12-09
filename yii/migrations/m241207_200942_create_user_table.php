<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m241207_200942_create_user_table extends Migration
{
    public function safeUp(): void
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'password_hash' => $this->string(255)->notNull(),
            'auth_key' => $this->string(32)->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'access_token' => $this->string(255)->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'deleted_at' => $this->integer()->null(),
        ], $tableOptions);

        $this->createIndex('idx-user-username', 'user', 'username', true);
        $this->createIndex('idx-user-email', 'user', 'email', true);
        $this->createIndex('idx-user-password_reset_token', 'user', 'password_reset_token', true);
        $this->createIndex('idx-user-access_token', 'user', 'access_token', true);

    }

    public function safeDown(): void
    {
        $this->dropTable('{{%user}}');
    }
}
