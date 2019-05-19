<?php

use yii\db\Migration;

/**
 * Class m190519_200138_location_extend_table
 */
class m190519_200138_location_extend_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('location', 'dataUpdatedAt', $this->integer()->notNull()->defaultValue(0)->after('name'));
        $this->addColumn('location', 'dataHandledAt', $this->integer()->notNull()->defaultValue(0)->after('dataUpdatedAt'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('location', 'dataHandledAt');
        $this->dropColumn('location', 'dataUpdatedAt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190519_200138_location_extend_table cannot be reverted.\n";

        return false;
    }
    */
}
