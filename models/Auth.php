<?php

namespace app\models;


use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Auth model
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 *
 * @property User $user
 */
class Auth extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string {
        return '{{%auth}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'source', 'source_id'], 'required'],
            [['user_id'], 'int'],
            [['source', 'source_id'], 'string']
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
            'source' => 'Source',
            'source_id' => 'Source ID'
        ];
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
