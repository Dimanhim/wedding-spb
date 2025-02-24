<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;
use app\models\Manager;
use app\models\Mark;
use app\models\Category;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\ProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="collapse in" id="filter_block">
    <div class="well">
        <?php
            $form = ActiveForm::begin([
                'action' => Url::to(),
                'method' => 'get',
            ]);
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'manager_id' => [
                        'columnOptions' => ['colspan' => 12],
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', function($model, $defaultValue) {
                                return $model['surname'].' '.$model['name'];
                            }),
                            'options' => ['placeholder' => 'Все менеджеры'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                        'hint' => 'Выберите менеджера из списка'
                    ],
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
                    'category_id' => [
                        'columnOptions' => ['colspan' => 12],
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => ArrayHelper::map(Category::find()->orderBy('name ASC')->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Все категории'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                        'hint' => 'Выберите категорию из списка'
                    ],
                    'product_type' => [
                        'columnOptions' => ['colspan' => 12],
                        'type' => Form::INPUT_WIDGET, 
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => [1 => 'Платья', 2 => 'Прочие товары'],
                            'options' => ['placeholder' => 'Все товары'],
                            'pluginOptions' => [
                                'allowClear' => true
                            ],
                        ],
                        'hint' => 'Выберите категорию из списка'
                    ],
                    'created_at_begin' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => '\kartik\date\DatePicker',
                        'columnOptions' => ['colspan' => 12],
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
                ]
            ]);
        ?>
        <div class="form-group text-right">
            <?= Html::a('Сбросить', Url::to(), ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>