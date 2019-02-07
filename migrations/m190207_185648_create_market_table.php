<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%market}}`.
 */
class m190207_185648_create_market_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%market}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(32)->notNull(),
            'name' => $this->string(64)->notNull(),
            'logo' => $this->string(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%market}}');
    }
}
