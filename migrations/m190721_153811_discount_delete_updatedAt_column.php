<?php

use yii\db\Migration;

/**
 * Class m190721_153811_discount_delete_updatedAt_column
 */
class m190721_153811_discount_delete_updatedAt_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('discount', 'updatedAt');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190721_153811_discount_delete_updatedAt_column cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_153811_discount_delete_updatedAt_column cannot be reverted.\n";

        return false;
    }
    */
}
