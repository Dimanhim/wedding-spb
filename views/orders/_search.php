<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use app\models\Mark;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collapse in" id="filter_block">
    <div class="well">
        <?php
            $form = ActiveForm::begin([
                'action' => ['index'],
                'method' => 'get',
            ]);
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'marka_id' => [
                        'columnOptions' => ['colspan' => 12],
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => ArrayHelper::map(Mark::find()->orderBy('name ASC')->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Все марки'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                        'hint' => 'Выберите марку из списка'
                    ],
                    'created_at_begin' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => '\kartik\date\DatePicker',
                        'columnOptions' => ['colspan' => 6],
                        'options' => [
                            'language' => 'ru',
                            'attribute' => 'created_at_begin',
                            'type' => DatePicker::TYPE_RANGE,
                            'attribute2' => 'created_at_end',
                            'separator' => 'До',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ],
                    ],
                    'await_date_begin' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => '\kartik\date\DatePicker',
                        'columnOptions' => ['colspan' => 6],
                        'options' => [
                            'language' => 'ru',
                            'attribute' => 'await_date_begin',
                            'type' => DatePicker::TYPE_RANGE,
                            'attribute2' => 'await_date_end',
                            'separator' => 'До',
                            'pluginOptions' => [
                                'autoclose' => true,
                            ]
                        ],
                    ],
                ]
            ]);
        ?>
        <div class="form-group text-right">
            <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>