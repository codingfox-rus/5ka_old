<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%product}}`.
 */
class m190512_120424_create_product_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%product}}', [
            'id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'imageSmall' => $this->string(1024),
            'imageBig' => $this->string(1024),
            'previewSmall' => $this->string(1024),
            'previewBig' => $this->string(1024),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product}}');
    }
}
