<?php

use yii\db\Migration;

/**
 * Class m190310_131102_discount_add_url
 */
class m190310_131102_discount_add_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'url', $this->string(255)->after('productName'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'url');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190310_131102_discount_add_url cannot be reverted.\n";

        return false;
    }
    */
}
