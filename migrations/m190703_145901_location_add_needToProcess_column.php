<?php

use yii\db\Migration;

/**
 * Class m190703_145901_location_add_needToProcess_column
 */
class m190703_145901_location_add_needToProcess_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('location', 'needToProcess', $this->tinyInteger(1)->notNull()->defaultValue(0)->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('location', 'needToProcess');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_145901_location_add_needToProcess_column cannot be reverted.\n";

        return false;
    }
    */
}
