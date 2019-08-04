<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tag_key}}`.
 */
class m190804_190643_create_tag_key_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tag_key}}', [
            'id' => $this->primaryKey(),
            'tagId' => $this->integer()->notNull(),
            'key' => $this->string()->notNull(),
            'updatedAt' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%tag_key}}');
    }
}
