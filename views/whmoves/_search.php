<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use kartik\date\DatePicker;

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