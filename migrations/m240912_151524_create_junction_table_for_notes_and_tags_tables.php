<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%notes_tags}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%notes}}`
 * - `{{%tags}}`
 */
class m240912_151524_create_junction_table_for_notes_and_tags_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%notes_tags}}', [
            'notes_id' => $this->integer(),
            'tags_id' => $this->integer(),
            'PRIMARY KEY(notes_id, tags_id)',
        ]);

        $this->createIndex('{{%idx-notes_tags-notes_id}}', '{{%notes_tags}}', 'notes_id');
        $this->createIndex('{{%idx-notes_tags-tags_id}}', '{{%notes_tags}}', 'tags_id');

        $this->addForeignKey('{{%fk-notes_tags-notes_id}}', '{{%notes_tags}}', 'notes_id',
            '{{%notes}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('{{%fk-notes_tags-tags_id}}', '{{%notes_tags}}', 'tags_id',
            '{{%tags}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('{{%fk-notes_tags-tags_id}}', '{{%notes_tags}}');
        $this->dropForeignKey('{{%fk-notes_tags-notes_id}}', '{{%notes_tags}}');

        $this->dropIndex('{{%idx-notes_tags-tags_id}}', '{{%notes_tags}}');
        $this->dropIndex('{{%idx-notes_tags-notes_id}}', '{{%notes_tags}}');

        $this->dropTable('{{%notes_tags}}');
    }
}
