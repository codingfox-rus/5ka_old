<?php

use yii\db\Migration;

/**
 * Class m190124_183952_add_status_to_discount
 */
class m190124_183952_add_status_to_discount extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('discount', 'status', $this->tinyInteger(1)->defaultValue(1)->after('jsonData'));
        $this->dropColumn('discount', 'deletedAt');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190124_183952_add_status_to_discount cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190124_183952_add_status_to_discount cannot be reverted.\n";

        return false;
    }
    */
}
