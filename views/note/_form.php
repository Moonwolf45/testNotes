<?php

use kartik\select2\Select2;
use mihaildev\ckeditor\CKEditor;
use mihaildev\elfinder\ElFinder;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

mihaildev\elfinder\Assets::noConflict($this);

/** @var yii\web\View $this */
/** @var app\models\Notes $model */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\Tags $tags */
?>

<div class="notes-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]); ?>

    <?= $form->field($model, 'text')->widget(CKEditor::class, [
        'editorOptions' => ElFinder::ckeditorOptions('elfinder')
    ]); ?>

    <?= $form->field($model, 'tagsLink')->widget(Select2::class, [
        'theme' => Select2::THEME_KRAJEE_BS5,
        'data' => ArrayHelper::map($tags, 'id', 'title'),
        'options' => ['placeholder' => 'Выберите теги', 'multiple' => true],
        'pluginOptions' => [
            'allowClear' => true
        ]
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']); ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
