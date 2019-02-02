<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category_word_key`.
 */
class m181216_193622_create_category_word_key_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category_word_key', [
            'id' => $this->primaryKey(),
            'categoryId' => $this->integer()->notNull(),
            'key' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk_cat_id', 'category_word_key', 'categoryId', 'category', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_cat_id', 'category_word_key');

        $this->dropTable('category_word_key');
    }
}
