<?php

use yii\db\Migration;

/**
 * Class m190721_114758_settings_fill_table
 */
class m190721_114758_settings_fill_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('settings', [
            'apiVersion' => 2,
            'updatedAt' => time(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190721_114758_settings_fill_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190721_114758_settings_fill_table cannot be reverted.\n";

        return false;
    }
    */
}
