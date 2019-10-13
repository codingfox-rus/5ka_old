<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tag_discount}}`.
 */
class m191013_180004_create_tag_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_discount}}', [
            'tagId' => $this->integer()->notNull(),
            'discountId' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk_tag_discount', 'tag_discount', ['tagId', 'discountId']);

        $this->addForeignKey('fk_td_tag', 'tag_discount', 'tagId', 'tag', 'id', 'cascade', 'cascade');

        $this->addForeignKey('fk_td_discount', 'tag_discount', 'discountId', 'discount', 'id', 'cascade', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addForeignKey('fk_td_discount', 'tag_discount', 'discountId', 'discount', 'id', 'cascade', 'cascade');

        $this->addForeignKey('fk_td_tag', 'tag_discount', 'tagId', 'tag', 'id', 'cascade', 'cascade');

        $this->dropPrimaryKey('pk_tag_discount', 'tag_discount');

        $this->dropTable('{{%tag_discount}}');
    }
}
