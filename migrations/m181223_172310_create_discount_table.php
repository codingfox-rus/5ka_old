<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount`.
 */
class m181223_172310_create_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('discount', [
            'id' => $this->primaryKey(),
            'categoryId' => $this->integer(),
            'market' => $this->string(32)->notNull(),
            'productName' => $this->string()->notNull(),
            'description' => $this->text(),
            'condition' => $this->text(),
            'imageSmall' => $this->string(512),
            'imageBig' => $this->string(512),
            'regularPrice' => $this->decimal(8,2)->notNull(),
            'specialPrice' => $this->decimal(8,2)->notNull(),
            'discountPercent' => $this->decimal(5,2),
            'dateStart' => $this->integer(),
            'dateEnd' => $this->integer(),
            'jsonData' => $this->text(),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
            'deletedAt' => $this->integer(),
        ]);

        $this->addForeignKey('fk_discount_category_id', 'discount', 'categoryId', 'category', 'id', 'set null', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_discount_category_id', 'discount');

        $this->dropTable('discount');
    }
}
