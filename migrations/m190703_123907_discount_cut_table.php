<?php

use yii\db\Migration;

/**
 * Class m190703_123907_discount_cut_table
 */
class m190703_123907_discount_cut_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_discount_category_id', 'discount');

        $this->dropColumn('discount', 'categoryId');
        $this->dropColumn('discount', 'url');
        $this->dropColumn('discount', 'description');
        $this->dropColumn('discount', 'condition');

        $this->alterColumn('discount', 'jsonData', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190703_123907_discount_cut_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190703_123907_discount_cut_table cannot be reverted.\n";

        return false;
    }
    */
}
