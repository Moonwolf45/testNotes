<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var app\models\Notes $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

YiiAsset::register($this); ?>
<div class="notes-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            [
                'attribute' => 'text',
                'format' => ['html'],
                'value' => function($data) {
                    return mb_strimwidth($data->text, 0, 250, "...");
                }
            ],
            [
                'attribute' => 'tags',
                'format' => ['html'],
                'value' => function($data) {
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
                'format' => 'datetime'
            ],
            [
                'attribute' => 'updated_at',
                'format' => 'datetime'
            ]
        ]
    ]) ?>

</div>
