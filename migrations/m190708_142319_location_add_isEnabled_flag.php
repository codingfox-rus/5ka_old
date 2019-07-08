<?php

use yii\db\Migration;

/**
 * Class m190708_142319_location_add_isEnabled_flag
 */
class m190708_142319_location_add_isEnabled_flag extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('location', 'isEnabled', $this->tinyInteger(1)->notNull()->defaultValue(0)->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('location', 'isEnabled');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190708_142319_location_add_isEnabled_flag cannot be reverted.\n";

        return false;
    }
    */
}
