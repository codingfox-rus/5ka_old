<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%discount_history}}`.
 */
class m200530_081653_drop_discount_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%discount_history}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%discount_history}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
