<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Закупки';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="search_block">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> заказов</div>',
        'columns' => [
            'id',
            [
                'attribute' => 'marka_id',
                'value'=> function($data) use ($marks) {
                    foreach ($data->items as $item) {
                        $marka_key = array_search($item->product->marka_id, array_column($marks, 'id'));
                        return ($marka_key !== false) ? $marks[$marka_key]['name'] : '-';
                    }
                    //return $data->marka ? $data->marka : '-';
                }
            ],
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
            // [
            //     'attribute' => 'payment_type',
            //     'value'=> function($data) {
            //         return $data->getPayCashLabel();
            //     }
            // ],
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
                    // return '<select id="order_items-51-delivery_status" class="form-control" name="order_items[51][delivery_status]">
                    //             <option value="1" selected="">инициализирована</option>
                    //             <option value="2">частично поступил</option>
                    //             <option value="3">полностью поступил</option>
                    //         </select>';
                    return $data->getDeliveryStatusLabel();
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{payment-part}{payment-full}{delivery-full}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                    'payment-part' => function ($url, $model, $key) {
                        if ($model->payment_status == $model::PAYMENT_INIT) {
                            return Html::a('<span class="glyphicon glyphicon-credit-card"></span>', $url, [
                                'class' => 'btn btn-warning btn-xs',
                                'title' => 'Чатсично оплачен',
                                'data-toggle' => 'modal',
                                'data-target' => '#partPay_'.$model->id
                            ]);
                        }
                    },
                    'payment-full' => function ($url, $model, $key) {
                        if ($model->payment_status != $model::PAYMENT_FULL) {
                            return Html::a('<span class="glyphicon glyphicon-credit-card"></span>', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'title' => 'Оплачен полностью',
                                'data-toggle' => 'modal',
                                'data-target' => '#fullPay_'.$model->id
                            ]);
                        }
                    },
                    'delivery-full' => function ($url, $model, $key) {
                        if (($model->delivery_status != $model::DELIVERY_FULL and $model->payment_status != $model::PAYMENT_INIT)) {
                            return Html::a('<span class="glyphicon glyphicon-envelope"></span>', $url, [
                                'class' => 'btn btn-success btn-xs',
                                'title' => 'Пришел полностью',
                                'data' => [
                                    'confirm' => 'Вы уверены, что заказ №'.$model->id.' пришел полностью?',
                                ]
                            ]);
                        }
                    },

                    //'update' => function ($url, $model, $key) {
                    //    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']);
                    //},
                    //'delete' => function ($url, $model, $key) {
                    //    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    //        'class' => 'btn btn-danger btn-xs',
                    //        'title' => 'Удалить',
                    //        'data' => [
                    //            'confirm' => 'Вы уверены, что хотите удалить заказ?',
                    //            'method' => 'post',
                    //        ],
                    //    ]);
                    //},
                ],
            ],
        ],
    ]); ?>

    <?php foreach ($dataProvider->getModels() as $model): ?>
        <!-- Modal -->
        <?php if ($model->payment_status == $model::PAYMENT_INIT): ?>
            <div class="modal fade" id="partPay_<?= $model->id ?>" tabindex="-1" role="dialog" aria-labelledby="partPay_<?= $model->id ?>Label">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="partPay_<?= $model->id ?>Label">Частичная оплата заказа №<?= $model->id ?></h4>
                        </div>
                        <div class="modal-body">
                            <?php
                                $form = ActiveForm::begin([
                                    'type'=>ActiveForm::TYPE_HORIZONTAL,
                                    'action' => Url::toRoute(['pay', 'id' => $model->id]),
                                ]);
                                echo Form::widget([
                                    'model' => $model,
                                    'form' => $form,
                                    'attributes' => [
                                        'total_payed' => [
                                            'type' => Form::INPUT_HTML5,
                                            'html5type' => 'number',
                                        ],
                                        'payment_type' => [
                                            'type' => Form::INPUT_DROPDOWN_LIST,
                                            'items'=> $model->getPaymentTypes(),
                                        ]
                                    ],
                                ]);
                            ?>
                            <p class="text-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>

        <?php if ($model->payment_status != $model::PAYMENT_FULL): ?>
            <div class="modal fade" id="fullPay_<?= $model->id ?>" tabindex="-1" role="dialog" aria-labelledby="fullPay_<?= $model->id ?>Label">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="fullPay_<?= $model->id ?>Label">Полная оплата заказа №<?= $model->id ?></h4>
                        </div>
                        <div class="modal-body">
                            <?php
                                $form = ActiveForm::begin([
                                    'type'=>ActiveForm::TYPE_HORIZONTAL,
                                    'action' => Url::toRoute(['full-pay', 'id' => $model->id]),
                                ]);
                                echo Form::widget([
                                    'model' => $model,
                                    'form' => $form,
                                    'attributes' => [
                                        'payment_type' => [
                                            'type' => Form::INPUT_DROPDOWN_LIST,
                                            'items'=> $model->getPaymentTypes(),
                                        ]
                                    ],
                                ]);
                            ?>
                            <p class="text-right"><?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?></p>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif ?>
    <?php endforeach ?>
</div>