<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%stat}}`.
 */
class m200530_081728_drop_stat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%stat}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%stat}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
