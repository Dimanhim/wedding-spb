<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Manager */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Менеджеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-view">
    <h1><?= Html::encode($this->title) ?></h1>

    <h2>Личная информация</h2>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'name',
            'surname',
            'fathername',
            [
                'attribute' => 'employment_date',
                'value' => date('d.m.Y', $model->employment_date),
            ],
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y', $model->updated_at),
            ],
        ],
    ]) ?>

    <h2>Отпуск и зарплата</h2>
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-group">
        <label class="control-label">Ближайший отпуск</label>
        <?= DatePicker::widget([
            'language' => 'ru',
            'name' => 'vacation_start',
            'value' => ($model->vacation_start) ? date('d.m.Y', $model->vacation_start) : date('d.m.Y'),
            'type' => DatePicker::TYPE_RANGE,
            'name2' => 'vacation_end',
            'value2' => ($model->vacation_end) ? date('d.m.Y', $model->vacation_end) : date('d.m.Y'),
            'separator' => 'До',
            'pluginOptions' => [
                'autoclose' => true,
            ]
        ]); ?>
    </div>
    <?= $form->field($model, 'salary_date')->widget(DateControl::classname(), [
        'language' => 'ru',
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
            ]
        ]
    ]); ?>
    <?= $form->field($model, 'advance_date')->widget(DateControl::classname(), [
        'language' => 'ru',
        'type' => DateControl::FORMAT_DATE,
        'options' => [
            'pluginOptions' => [
                'autoclose' => true,
            ]
        ]
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

    <?php
        $receipts->pagination->pageParam = 'receipts-page';
        $receipts->sort->sortParam = 'receipts-sort';

        $receipt_items->pagination->pageParam = 'receipt_items-page';
        $receipt_items->sort->sortParam = 'receipt_items-sort';
    ?>

    <h2>Чеки</h2>
    <?= GridView::widget([
        'dataProvider' => $receipts,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> чеков</div>',
        'columns' => [
            'id',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->created_at);
                }
            ],
            'total_amount',
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0]
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['receipts/view', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                ],
            ],
        ],
    ]); ?>

    <h2>Проданные товары</h2>
    <?= GridView::widget([
        'dataProvider' => $receipt_items,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> чеков</div>',
        'columns' => [
            'id',
            [
                'attribute' => 'product_id',
                'label' => 'Марка',
                'value'=> function($data) {
                    return $data->product ? $data->product->marka->name : '-';
                }
            ],
            [
                'attribute' => 'product_id',
                'label' => 'Модель',
                'value'=> function($data) {
                    return $data->product ? $data->product->model->name : '-';
                }
            ],
            [
                'attribute' => 'product_id',
                'label' => 'Цвет',
                'value'=> function($data) {
                    return $data->product ? $data->product->color->name : '-';
                }
            ],
            [
                'attribute' => 'price',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'amount',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'sale',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'gift',
                'format'=> ['boolean']
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0]
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['receipts/view', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>