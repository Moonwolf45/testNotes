<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Notes $model */
/** @var app\models\Tags $tags */

$this->title = 'Update Notes: ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Notes', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="notes-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'tags' => $tags
    ]) ?>

</div>
