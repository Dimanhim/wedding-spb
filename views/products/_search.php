<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\builder\Form;
use app\models\Mark;
use app\models\Model;
use app\models\Color;
use app\models\Rate;
use app\models\Size;

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
            echo Html::hiddenInput('category_id', $category_id);
            echo Html::hiddenInput('is_deleted', $is_deleted);
            echo Html::hiddenInput('type', $type);
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'name' => [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 2],
                        'options' => ['placeholder' => 'Строка для поиска'],
                    ],
                    'marka_id' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => ArrayHelper::map(Mark::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Выберите марку'],
                        ],
                        'columnOptions' => ['colspan' => 3],
                        //'type' => Form::INPUT_DROPDOWN_LIST, 
                        //'items'=> ArrayHelper::map(Mark::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        //'columnOptions' => ['colspan' => 3],
                        //'options' => ['prompt' => 'Выберите марку'],
                    ],
                    'model_id' => [
                        'type' => Form::INPUT_WIDGET,
                        'widgetClass' => '\kartik\widgets\Select2', 
                        'options' => [
                            'data' => ArrayHelper::map(Model::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                            'options' => ['placeholder' => 'Выберите модель'],
                        ],
                        'columnOptions' => ['colspan' => 3],
                        // 'type' => Form::INPUT_DROPDOWN_LIST, 
                        // 'items'=> ArrayHelper::map(Model::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        // 'columnOptions' => ['colspan' => 3],
                        // 'options' => ['prompt' => 'Выберите модель'],
                    ],
                    'color_id' => [
                        'type' => Form::INPUT_DROPDOWN_LIST, 
                        'items'=> ArrayHelper::map(Color::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        'columnOptions' => ['colspan' => 2],
                        'options' => ['prompt' => 'Выберите цвет'],
                    ],
                    'size_id' => [
                        'label' => 'Размер',
                        'type' => Form::INPUT_DROPDOWN_LIST, 
                        'items'=> ArrayHelper::map(Size::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                        'columnOptions' => ['colspan' => 2],
                        'options' => ['prompt' => 'Выберите размер'],
                    ],
                    // 'ratio_id' => [
                    //     'type' => Form::INPUT_DROPDOWN_LIST, 
                    //     'items'=> ArrayHelper::map(Rate::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    //     'columnOptions' => ['colspan' => 3],
                    //     'options' => ['prompt' => 'Выберите коэффициент'],
                    // ],
                ]
            ]);
        ?>
        <!-- <div class="form-group text-right">
            <?= Html::a('Сбросить', ['index', 'category_id' => $category_id], ['class' => 'btn btn-default']) ?>
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary']) ?>
        </div> -->
        <?php ActiveForm::end(); ?>
    </div>
</div>