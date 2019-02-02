<?php

use yii\db\Migration;

/**
 * Handles the creation of table `discount`.
 */
class m181215_145505_create_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('discount', [
            'id' => $this->primaryKey(),
            'itemId' => $this->integer(),
            'name' => $this->string(),
            'description' => $this->text(),
            'imageSmall' => $this->text(),
            'imageBig' => $this->text(),
            'paramId' => $this->integer(),
            'specialPrice' => $this->decimal(8, 2),
            'regularPrice' => $this->decimal(8, 2),
            'discountPercent' => $this->decimal(5, 2),
            'dateStart' => $this->integer(),
            'dateEnd' => $this->integer(),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('discount');
    }
}
