<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use app\models\Manager;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="operation-form">

    <?php
        $model->months = explode(',', $model->months);
        $model->days = explode(',', $model->days);
        $model->week = explode(',', $model->week);
        $form = ActiveForm::begin();
    ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'user_id')->dropDownList(ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => 'Выберите менеджера']) ?>
    <?= $form->field($model, 'type_id')->dropDownList($model->getTypes(), ['prompt' => 'Выберите тип']) ?>
    <?= $form->field($model, 'cat_id')->dropDownList($model->getCategories(), ['prompt' => 'Выберите категорию']) ?>
    <?= $form->field($model, 'payment_type')->dropDownList($model->getPayments(), ['prompt' => 'Выберите способ оплаты']) ?>
    <?= $form->field($model, 'total_price')->textInput() ?>
    <?= $form->field($model, 'repeated')->checkbox() ?>
    <?= $form->field($model, 'created_at')->widget(DateControl::classname(), [
        'language' => 'ru',
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
                //'endDate' => 'today'
            ]
        ]
    ]); ?>
    
    <div class="repeating_settings">
        <div class="checkbox_list">
            <?= $form->field($model, 'months')->checkboxList($model->allMonths()) ?>
            <div class="fastCheck">
                <a href="#" rel="chk_all">Выбрать все</a>
                <a href="#" rel="unchk_all">Убрать все</a>
            </div>
            <div class="fastCheck">
                <a href="#" rel="winter">Зима</a>
                <a href="#" rel="spring">Весна</a>
                <a href="#" rel="summer">Лето</a>
                <a href="#" rel="autumn">Осень</a>
            </div>
        </div>

        <div class="checkbox_list">
            <?= $form->field($model, 'days')->checkboxList($model->allDays()) ?>
            <div class="fastCheck">
                <a href="#" rel="chk_all">Выбрать все</a>
                <a href="#" rel="unchk_all">Убрать все</a>
            </div>
            <div class="fastCheck">
                <a href="#" rel="even">Четные</a>
                <a href="#" rel="odd">Нечетные</a>
            </div>
        </div>

        <div class="checkbox_list">
            <?= $form->field($model, 'week')->checkboxList($model->allWeek()) ?>
            <div class="fastCheck">
                <a href="#" rel="chk_all">Выбрать все</a>
                <a href="#" rel="unchk_all">Убрать все</a>
            </div>
            <div class="fastCheck">
                <a href="#" rel="work">Будние</a>
                <a href="#" rel="weekend">Выходные</a>
            </div>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
