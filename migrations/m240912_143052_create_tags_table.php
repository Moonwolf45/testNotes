<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tags}}`.
 */
class m240912_143052_create_tags_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tags}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull()
        ]);

        $this->addForeignKey('fk-tags-user_id-user-id', '{{%tags}}', 'user_id', '{{%user}}',
            'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-tags-user_id-user-id', '{{%tags}}');

        $this->dropTable('{{%tags}}');
    }
}
