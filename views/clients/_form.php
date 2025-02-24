<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Manager;
use app\models\Size;
use app\models\Product;
use kartik\date\DatePicker;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Client */
/* @var $form yii\widgets\ActiveForm */

if ($model->first_visit) $model->first_visit_field = date('d.m.Y', $model->first_visit);
if ($model->birtday) $model->birtday_field = date('d.m.Y', $model->birtday);
if ($model->event_date) $model->event_date_field = date('d.m.Y', $model->event_date);
if ($model->sizes) $model->sizes_field = explode(',', $model->sizes);
if ($model->products) {
    $model->products_field = [];
    foreach ($model->products as $client_product) {
        $model->products_field[] = $client_product->product_id;
    }
}
?>

<div class="client-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'manager_id')->dropDownList(ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', function($model) {
        return $model->fio;
    }), ['prompt' => 'Не выбран']) ?>
    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'first_visit_field')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Выберите дату'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>
    <?= $form->field($model, 'visit_purpose')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'is_appoint')->checkbox() ?>
    <?= $form->field($model, 'birtday_field')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Выберите дату'],
        'pluginOptions' => [
            'autoclose' => true,
        ]
    ]); ?>
    <?= $form->field($model, 'event_date_field')->widget(DatePicker::classname(), [
        'options' => ['placeholder' => 'Выберите дату'],
        'pluginOptions' => [
            'autoclose' => true
        ]
    ]); ?>
    <?= $form->field($model, 'sizes_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Size::find()->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите размеры', 'multiple' => true],
    ]) ?>
    <?= $form->field($model, 'wedding_place')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'source')->dropDownList($model->sourceArr, ['prompt' => '-']) ?>
    <?= $form->field($model, 'products_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Product::find()->where(['in', 'category_id', [1, 2]])->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите товары', 'multiple' => true],
    ]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>