<?php

use yii\db\Migration;

/**
 * Class m190512_122715_discount_add_locationId
 */
class m190512_122715_discount_add_locationId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'locationId', $this->integer()->after('market'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'locationId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190512_122715_discount_add_locationId cannot be reverted.\n";

        return false;
    }
    */
}
