<?php

use yii\db\Migration;

/**
 * Class m190721_113215_discount_delete_market
 */
class m190721_113215_discount_delete_market extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('discount', 'market');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190721_113215_discount_delete_market cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_113215_discount_delete_market cannot be reverted.\n";

        return false;
    }
    */
}
