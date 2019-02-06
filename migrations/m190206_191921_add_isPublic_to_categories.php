<?php

use yii\db\Migration;

/**
 * Class m190206_191921_add_isPublic_to_categories
 */
class m190206_191921_add_isPublic_to_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('category', 'isPublic', $this->tinyInteger(1)->defaultValue(0));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('category', 'isPublic');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190206_191921_add_isPublic_to_categories cannot be reverted.\n";

        return false;
    }
    */
}
