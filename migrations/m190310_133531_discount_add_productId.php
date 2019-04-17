<?php

use yii\db\Migration;

/**
 * Class m190310_133531_discount_add_productId
 */
class m190310_133531_discount_add_productId extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'productId', $this->integer()->after('market'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'productId');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190310_133531_discount_add_productId cannot be reverted.\n";

        return false;
    }
    */
}
