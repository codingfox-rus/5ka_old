<?php

use yii\db\Migration;

/**
 * Class m190512_123644_discount_delete_img_columns
 */
class m190512_123644_discount_delete_img_columns extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('discount', 'imageSmall');
        $this->dropColumn('discount', 'imageBig');
        $this->dropColumn('discount', 'previewSmall');
        $this->dropColumn('discount', 'previewBig');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190512_123644_discount_delete_img_columns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190512_123644_discount_delete_img_columns cannot be reverted.\n";

        return false;
    }
    */
}
