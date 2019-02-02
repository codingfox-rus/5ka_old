<?php

use yii\db\Migration;

/**
 * Class m181215_165050_rename_discount_table
 */
class m181215_165050_rename_discount_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('discount', 'discount_five_shop');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameTable('discount_five_shop', 'discount');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181215_165050_rename_discount_table cannot be reverted.\n";

        return false;
    }
    */
}
