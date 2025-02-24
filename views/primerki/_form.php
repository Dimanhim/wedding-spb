<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Manager;
use app\models\Client;
use app\models\Product;
use app\models\Receipt;
use kartik\widgets\DateTimePicker;
use kartik\widgets\Select2;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $model app\models\Primerka */
/* @var $form yii\widgets\ActiveForm */

if ($model->date) $model->date_field = date('d.m.Y H:i', $model->date);
if ($model->wishes) {
    $model->wishes_field = [];
    foreach ($model->wishes as $primerka_product) {
        $model->wishes_field[] = $primerka_product->product_id;
    }
}
if ($model->products) {
    $model->products_field = [];
    foreach ($model->products as $primerka_product) {
        $model->products_field[] = $primerka_product->product_id;
    }
}
?>

<div class="primerka-form">
    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'manager_id')->dropDownList(ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', function($model) {
        return $model->fio;
    }), ['prompt' => 'Не выбран']) ?>
    <?= $form->field($model, 'client_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Client::find()->orderBy('fio ASC')->all(), 'id', function($data) {
            return $data->fio.' - '.$data->shortPhone;
        }),
        'options' => ['placeholder' => 'Не выбран'],
        'pluginOptions' => [
            'language' => ['noResults' => new JsExpression("function (params) {
                return '<button type=\"button\" id=\"save-phone\">Сохранить телефон</button><button type=\"button\" id=\"save-name\">Сохранить имя</button>';
            }")],
            'escapeMarkup' => new JsExpression('function(markup) {return markup;}'),
        ]
    ]) ?>
    <p><strong>или создайте нового</strong></p>
    <div class="row">
        <div class="col-lg-2 col-md-3">
            <?= $form->field($model, 'client_fio')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-lg-2 col-md-3">
            <?= $form->field($model, 'client_phone')->textInput(['maxlength' => true]) ?>
        </div>
    </div>
    <?= $form->field($model, 'date_field')->textInput(['maxlength' => true, 'autocomplete' => 'off']) ?>
    <?= $form->field($model, 'wishes_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Product::find()->where(['in', 'category_id', [1, 2]])->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите товары', 'multiple' => true],
    ]) ?>
    <?= $form->field($model, 'products_field')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Product::find()->where(['in', 'category_id', [1, 2]])->orderBy('name ASC')->all(), 'id', 'name'),
        'options' => ['placeholder' => 'Выберите товары', 'multiple' => true],
    ]) ?>
    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>
    <?= $form->field($model, 'result')->dropDownList($model->resultArr, ['prompt' => '-']) ?>
    <?= $form->field($model, 'receipt_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Receipt::find()->orderBy('id DESC')->all(), 'id', function($model) {
            return 'Чек №'.$model->id;
        }),
        'options' => ['placeholder' => 'Выберите чек'],
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>