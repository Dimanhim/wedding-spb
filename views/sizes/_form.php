<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $model app\models\Size */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="size-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name'), ['prompt' => 'Выберите категорию']) ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>