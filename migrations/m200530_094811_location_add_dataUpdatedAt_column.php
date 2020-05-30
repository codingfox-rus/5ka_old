<?php

use yii\db\Migration;

/**
 * Class m200530_094811_location_add_dataUpdatedAt_column
 */
class m200530_094811_location_add_dataUpdatedAt_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('location', 'dataUpdatedAt', $this->integer());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200530_094811_location_add_dataUpdatedAt_column cannot be reverted.\n";

        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200530_094811_location_add_dataUpdatedAt_column cannot be reverted.\n";

        return false;
    }
    */
}
