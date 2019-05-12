<?php

use yii\db\Migration;

/**
 * Class m190512_141352_product_recreate_table
 */
class m190512_141352_product_recreate_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('product');

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'pId' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'imageSmall' => $this->string(1024),
            'imageBig' => $this->string(1024),
            'previewSmall' => $this->string(1024),
            'previewBig' => $this->string(1024),
            'createdAt' => $this->integer(),
            'updatedAt' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190512_141352_product_recreate_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190512_141352_product_recreate_table cannot be reverted.\n";

        return false;
    }
    */
}
