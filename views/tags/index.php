<?php

use app\models\search\TagsSearch;
use app\models\Tags;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var TagsSearch $searchModel */

$this->title = 'Tags';
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="tags-index">
    <h1><?= Html::encode($this->title); ?></h1>

    <p>
        <?= Html::a('Create Tags', ['create'], ['class' => 'btn btn-success']); ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'title',
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
                'urlCreator' => function ($action, Tags $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ]
        ]
    ]); ?>
</div>
