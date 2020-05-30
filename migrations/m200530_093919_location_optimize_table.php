<?php

use yii\db\Migration;

/**
 * Class m200530_093919_location_optimize_table
 */
class m200530_093919_location_optimize_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('location', 'dataUpdatedAt');
        $this->dropColumn('location', 'dataHandledAt');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200530_093919_location_optimize_table cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200530_093919_location_optimize_table cannot be reverted.\n";

        return false;
    }
    */
}
