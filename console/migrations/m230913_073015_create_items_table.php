<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%items}}`.
 */
class m230913_073015_create_items_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%items}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'category' => $this->integer()->notNull(),
            'price' => $this->float(2)->notNull(),
            'currency' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%items}}');
    }
}
