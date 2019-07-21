<?php

use yii\db\Migration;

/**
 * Class m190721_135545_product_cut_table
 */
class m190721_135545_product_cut_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('product', 'imageBig');
        $this->dropColumn('product', 'previewBig');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190721_135545_product_cut_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_135545_product_cut_table cannot be reverted.\n";

        return false;
    }
    */
}
