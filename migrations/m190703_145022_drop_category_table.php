<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%category}}`.
 */
class m190703_145022_drop_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%category}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
        ]);
    }
}
