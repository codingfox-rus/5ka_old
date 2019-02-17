<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m190217_194609_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'email' => $this->string()->notNull(),
            'password' => $this->string(),
            'activationCode' => $this->string(),
            'activationRequestAt' => $this->integer(),
            'authKey' => $this->string(),
            'accessToken' => $this->string(),
            'status' => $this->tinyInteger()->defaultValue(1),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
