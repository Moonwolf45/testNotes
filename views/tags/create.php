<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Tags $model */

$this->title = 'Create Tags';
$this->params['breadcrumbs'][] = ['label' => 'Tags', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title; ?>

<div class="tags-create">
    <h1><?= Html::encode($this->title); ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]); ?>
</div>
