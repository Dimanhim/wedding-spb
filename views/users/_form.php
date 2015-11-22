<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'fathername')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'employment_date')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'auth_key')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_hash')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'password_reset_token')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email_confirm_token')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'role')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'status')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>