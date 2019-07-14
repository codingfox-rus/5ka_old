<?php

use yii\db\Migration;

/**
 * Class m190714_143528_region_add_updatedAt_ts
 */
class m190714_143528_region_add_updatedAt_ts extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('region', 'updatedAt', $this->integer()->notNull()->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('region', 'updatedAt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190714_143528_region_add_updatedAt_ts cannot be reverted.\n";

        return false;
    }
    */
}
