<?php

use yii\db\Migration;

/**
 * Class m190124_175959_drop_unused_tables
 */
class m190124_175959_drop_unused_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('category_word_key');
        $this->dropTable('discount_five_shop');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190124_175959_drop_unused_tables cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190124_175959_drop_unused_tables cannot be reverted.\n";

        return false;
    }
    */
}
