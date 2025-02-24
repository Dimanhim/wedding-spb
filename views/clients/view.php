<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Client */

$this->title = $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Клиенты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="client-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'manager_id',
                'format' => 'raw',
                'value' => $model->manager ? Html::a($model->manager->fio, ['managers/view', 'id' => $model->manager_id]) : '-',
            ],
            'fio',
            'phone',
            'email:email',
            [
                'attribute' => 'first_visit',
                'value' => date('d.m.Y', $model->first_visit),
            ],
            'visit_purpose',
            'is_appoint:boolean',
            [
                'attribute' => 'birtday',
                'value' => date('d.m.Y', $model->birtday),
            ],
            [
                'attribute' => 'event_date',
                'value' => date('d.m.Y', $model->event_date),
            ],
            [
                'attribute' => 'sizes',
                'value' => $model->formattedSizes,
            ],
            'wedding_place',
            [
                'attribute' => 'source',
                'value' => $model->sourceName,
            ],
            [
                'attribute' => 'products_field',
                'format' => 'raw',
                'value' => $model->formattedProducts,
            ],
            'description:ntext',
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i', $model->updated_at),
            ],
        ],
    ]) ?>
    <hr>
    <div class="tab-pane" id="primerki">
        <h3>Примерки</h3>
        <?= GridView::widget([
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $model->primerki,
                'key' => 'id',
            ]),
            'columns' => [
                'id',
                [
                    'attribute' => 'manager_id',
                    'format' => 'raw',
                    'value' => function($data) {
                        return ($data->manager) ? Html::a($data->manager->fio, ['managers/view', 'id' => $data->manager_id]) : '-';
                    },
                ],
                [
                    'attribute' => 'date',
                    'value'=> function($data) {
                        return date('d.m.Y H:i', $data->date);
                    }
                ],
                // 'description:ntext',
                [
                    'attribute' => 'result',
                    'value'=> function($data) {
                        return $data->resultName;
                    }
                ],
                [
                    'attribute' => 'receipt_id',
                    'format' => 'raw',
                    'value' => function($data) {
                        return ($data->receipt) ? Html::a('Чек №'.$data->receipt->id, ['receipts/view', 'id' => $data->receipt_id]) : '-';
                    },
                ],
                // 'receipt_id',
                [
                    'attribute' => 'created_at',
                    'value'=> function($data) {
                        return date('d.m.Y H:i', $data->created_at);
                    }
                ],
                // 'updated_at',
                ['class' => 'yii\grid\ActionColumn', 'controller' => 'primerki'],
            ],
        ]); ?>
    </div>
</div>