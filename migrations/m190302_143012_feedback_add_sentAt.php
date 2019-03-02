<?php

use yii\db\Migration;

/**
 * Class m190302_143012_feedback_add_sentAt
 */
class m190302_143012_feedback_add_sentAt extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('feedback', 'sentAt', $this->integer()->after('updatedAt'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('feedback', 'sentAt');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190302_143012_feedback_add_sentAt cannot be reverted.\n";

        return false;
    }
    */
}
