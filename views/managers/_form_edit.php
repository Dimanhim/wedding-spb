<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $manager app\models\Manager */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="manager-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->errorSummary($manager) ?>
    <?= $form->field($manager, 'surname')->textInput(['maxlength' => true]) ?>
    <?= $form->field($manager, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($manager, 'fathername')->textInput(['maxlength' => true]) ?>
    <?= $form->field($manager, 'employment_date')->widget(DateControl::classname(), [
        'language' => 'ru',
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
                'endDate' => 'today'
            ]
        ]
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton($manager->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $manager->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
