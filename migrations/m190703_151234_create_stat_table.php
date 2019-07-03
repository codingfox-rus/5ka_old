<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%stat}}`.
 */
class m190703_151234_create_stat_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%stat}}', [
            'locationId' => $this->integer(),
            'productId' => $this->integer(),
            'data' => $this->json(),
            'nextTurnAt' => $this->integer()->notNull()->defaultValue(0),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);

        $this->addPrimaryKey('pk_stat', 'stat', ['locationId', 'productId']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('pk_stat', 'stat');

        $this->dropTable('{{%stat}}');
    }
}
