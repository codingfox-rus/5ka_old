<?php

use yii\db\Migration;

/**
 * Class m181217_192654_discount_five_shop_add_categoryId
 */
class m181217_192654_discount_five_shop_add_categoryId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount_five_shop', 'categoryId', $this->integer()->after('id'));

        $this->addForeignKey('fk_dfs_catId', 'discount_five_shop', 'categoryId', 'category', 'id', 'set null', 'cascade');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_dfs_catId', 'discount_five_shop');

        $this->dropColumn('discount_five_shop', 'categoryId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181217_192654_discount_five_shop_add_categoryId cannot be reverted.\n";

        return false;
    }
    */
}
