<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Mark */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="mark-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'category_id')->textInput() ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>