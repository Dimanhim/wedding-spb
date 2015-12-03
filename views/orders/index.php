<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> заказов</div>',
        'columns' => [
            'id',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
            [
                'attribute' => 'await_date',
                'value'=> function($data) {
                    return date('d.m.Y', $data->await_date);
                }
            ],
            'total_amount',
            [
                'attribute' => 'payment',
                'value'=> function($data) {
                    return $data->getPayCashLabel();
                }
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'total_payed',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'total_rest',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'payment_status',
                'format' => 'raw',
                'value'=> function($data) {
                    return $data->getPaymentStatusLabel();
                }
            ],
            [
                'attribute' => 'delivery_status',
                'format' => 'raw',
                'value'=> function($data) {
                    return $data->getDeliveryStatusLabel();
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить заказ?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>