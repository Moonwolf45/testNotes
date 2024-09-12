<?php

use app\models\Notes;
use app\models\search\NotesSearch;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var NotesSearch $searchModel */

$this->title = 'Notes';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="notes-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Create Notes', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'text',
                'format' => ['html'],
                'content' => function($data) {
                    return mb_strimwidth($data->text, 0, 250, "...");
                }
            ],
            [
                'attribute' => 'tags',
                'format' => ['html'],
                'content' => function($data) {
                    $tags = '';
                    if (!empty($data->tags)) {
                        foreach ($data->tags as $tag) {
                            $tags .= '<a href="' . Url::to(['/note/tags', 'tags_id' => $tag->id]) . '">' . $tag->title . '</a><br>';
                        }
                    }

                    return $tags;
                }
            ],
            [
                'attribute' => 'created_at',
                'format' => 'datetime',
                'filter' => false
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime',
                'filter' => false
            ],
            [
                'class' => ActionColumn::class,
                'urlCreator' => function ($action, Notes $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ]
        ]
    ]); ?>
</div>
