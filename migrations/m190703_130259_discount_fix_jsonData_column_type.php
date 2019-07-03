<?php

use yii\db\Migration;

/**
 * Class m190703_130259_discount_fix_jsonData_column_type
 */
class m190703_130259_discount_fix_jsonData_column_type extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('discount', 'jsonData', $this->text());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190703_130259_discount_fix_jsonData_column_type cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_130259_discount_fix_jsonData_column_type cannot be reverted.\n";

        return false;
    }
    */
}
