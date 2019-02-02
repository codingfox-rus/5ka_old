<?php

use yii\db\Migration;

/**
 * Class m190202_144824_add_preview_fields_to_discount
 */
class m190202_144824_add_preview_fields_to_discount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'previewSmall', $this->string(512)->after('imageBig'));
        $this->addColumn('discount', 'previewBig', $this->string(512)->after('previewSmall'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('discount', 'previewSmall');
        $this->dropColumn('discount', 'previewBig');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190202_144824_add_preview_fields_to_discount cannot be reverted.\n";

        return false;
    }
    */
}
