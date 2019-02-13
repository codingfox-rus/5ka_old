<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%page}}`.
 */
class m190213_200326_create_page_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%page}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'keywords' => $this->text(),
            'content' => $this->text(),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%page}}');
    }
}
