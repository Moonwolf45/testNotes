<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%notes_tags}}".
 *
 * @property int $notes_id
 * @property int $tags_id
 *
 * @property Notes $notes
 * @property Tags $tags
 */
class NotesTags extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%notes_tags}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['notes_id', 'tags_id'], 'required'],
            [['notes_id', 'tags_id'], 'integer'],
            [['notes_id', 'tags_id'], 'unique', 'targetAttribute' => ['notes_id', 'tags_id']],
            [['notes_id'], 'exist', 'skipOnError' => true, 'targetClass' => Notes::class,
                'targetAttribute' => ['notes_id' => 'id']],
            [['tags_id'], 'exist', 'skipOnError' => true, 'targetClass' => Tags::class,
                'targetAttribute' => ['tags_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'notes_id' => 'Notes ID',
            'tags_id' => 'Tags ID'
        ];
    }

    /**
     * Gets query for [[Notes]].
     *
     * @return ActiveQuery
     */
    public function getNotes(): ActiveQuery
    {
        return $this->hasOne(Notes::class, ['id' => 'notes_id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return ActiveQuery
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasOne(Tags::class, ['id' => 'tags_id']);
    }
}
