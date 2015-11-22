<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'marka')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'artikul')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'color')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'photo')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'purchase_price_small')->textInput() ?>
    <?= $form->field($model, 'purchase_price_big')->textInput() ?>
    <?= $form->field($model, 'purchase_price_small_dol')->textInput() ?>
    <?= $form->field($model, 'purchase_price_big_dol')->textInput() ?>
    <?= $form->field($model, 'recommended_price_small')->textInput() ?>
    <?= $form->field($model, 'recommended_price_big')->textInput() ?>
    <?= $form->field($model, 'price_small')->textInput() ?>
    <?= $form->field($model, 'price_big')->textInput() ?>
    <?= $form->field($model, 'price_ratio')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>