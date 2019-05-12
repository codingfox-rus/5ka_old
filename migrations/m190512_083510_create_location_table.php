<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%location}}`.
 */
class m190512_083510_create_location_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%location}}', [
            'id' => $this->integer()->notNull(),
            'regionId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk_regionId', 'location', 'regionId', 'region', 'id', 'cascade', 'cascade');

        $this->addPrimaryKey('pk_location', 'location', ['id', 'regionId']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropPrimaryKey('pk_location', 'location');

        $this->dropForeignKey('fk_regionId', 'location');

        $this->dropTable('{{%location}}');
    }
}
