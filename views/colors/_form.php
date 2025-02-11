<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\SiteColor;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Color */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="color-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'category_id')->textInput() ?>
    <?= $form->field($model, 'site_color_id')->dropDownList(ArrayHelper::map(SiteColor::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => 'Выберите цвет для сайта']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>