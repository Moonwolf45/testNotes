<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\User $model */

use yii\authclient\widgets\AuthChoice;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Авторизация';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title); ?></h1>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                    'labelOptions' => ['class' => 'col-lg-3 col-form-label mr-lg-3'],
                    'inputOptions' => ['class' => 'col-lg-3 form-control'],
                    'errorOptions' => ['class' => 'col-lg-7 invalid-feedback']
                ]
            ]); ?>

            <?= $form->field($model, 'email')->textInput(['autofocus' => true]); ?>

            <?= $form->field($model, 'password')->passwordInput(); ?>

            <div class="form-group">
                <div>
                    <?= Html::submitButton('Авторизация', ['class' => 'btn btn-primary', 'name' => 'login-button']); ?>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <?= Html::a('Регистрация', ['/site/register'], ['class' => 'btn btn-primary', 'name' => 'register-button']); ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <div class="col-lg-5">
            <div class="form-group">
                <div>
                    <?= AuthChoice::widget([
                        'baseAuthUrl' => ['/site/auth-vk'],
                        'popupMode' => false,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
