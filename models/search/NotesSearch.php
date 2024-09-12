<?php

namespace app\models\search;

use app\models\Notes;
use Yii;
use yii\data\ActiveDataProvider;

class NotesSearch extends Notes
{

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['id'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['text'], 'string'],
        ];
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {
        $query = static::find()->joinWith(['tags'])->where([Notes::tableName() . '.user_id' => Yii::$app->user->identity->id])
            ->groupBy([Notes::tableName() . '.id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 5
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([Notes::tableName() . '.id' => $this->id]);
        $query->andFilterWhere(['LIKE', Notes::tableName() . '.title', $this->title]);
        $query->andFilterWhere(['LIKE', Notes::tableName() . '.text', $this->text]);

        return $dataProvider;
    }

}