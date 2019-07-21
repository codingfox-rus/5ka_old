<?php

use yii\db\Migration;

/**
 * Class m190721_144059_region_add_capitalLocationId
 */
class m190721_144059_region_add_capitalLocationId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('region', 'capitalLocationId', $this->integer()->after('name'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('region', 'capitalLocationId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_144059_region_add_capitalLocationId cannot be reverted.\n";

        return false;
    }
    */
}
