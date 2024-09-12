<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%tags}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property int $created_at
 * @property int $updated_at
 *
 * @property Notes[] $notes
 * @property NotesTags[] $notesTags
 * @property User $user
 */
class Tags extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%tags}}';
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
            [['user_id', 'title'], 'required'],
            [['user_id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class,
                'targetAttribute' => ['user_id' => 'id']],
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
            'title' => 'Название'
        ];
    }

    /**
     * Gets query for [[Notes]].
     *
     * @return ActiveQuery
     *
     * @throws InvalidConfigException
     */
    public function getNotes(): ActiveQuery
    {
        return $this->hasMany(Notes::class, ['id' => 'notes_id'])->viaTable('{{%notes_tags}}', ['tags_id' => 'id']);
    }

    /**
     * Gets query for [[NotesTags]].
     *
     * @return ActiveQuery
     */
    public function getNotesTags(): ActiveQuery
    {
        return $this->hasMany(NotesTags::class, ['tags_id' => 'id']);
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
