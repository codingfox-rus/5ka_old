<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%discount_history}}`.
 */
class m190721_154151_create_discount_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%discount_history}}', [
            'id' => $this->primaryKey(),
            'locationId' => $this->integer()->notNull()->defaultValue(0),
            'productId' => $this->integer()->notNull()->defaultValue(0),
            'productName' => $this->string()->notNull()->defaultValue('-'),
            'regularPrice' => $this->decimal(8, 2)->notNull()->defaultValue(0),
            'specialPrice' => $this->decimal(8, 2)->notNull()->defaultValue(0),
            'discountPercent' => $this->decimal(5, 2)->notNull()->defaultValue(0),
            'dateStart' => $this->integer()->notNull()->defaultValue(0),
            'dateEnd' => $this->integer()->notNull()->defaultValue(0),
            'jsonData' => $this->text(),
            'status' => $this->tinyInteger(2)->notNull()->defaultValue(1),
            'createdAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%discount_history}}');
    }
}
