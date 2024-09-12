<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%notes}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $text
 * @property int $created_at
 * @property int $updated_at
 *
 * @property NotesTags[] $notesTags
 * @property Tags[] $tags
 * @property User $user
 */
class Notes extends ActiveRecord
{

    public $tagsLink;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%notes}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'title', 'text'], 'required'],
            [['user_id', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
            [['tagsLink'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'title' => 'Название',
            'text' => 'Текст',
            'tags' => 'Теги',
            'tagsLink' => 'Теги'
        ];
    }

    /**
     * Gets query for [[NotesTags]].
     *
     * @return ActiveQuery
     */
    public function getNotesTags(): ActiveQuery
    {
        return $this->hasMany(NotesTags::class, ['notes_id' => 'id']);
    }

    /**
     * Gets query for [[Tags]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTags(): ActiveQuery
    {
        return $this->hasMany(Tags::class, ['id' => 'tags_id'])->viaTable('{{%notes_tags}}', ['notes_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
