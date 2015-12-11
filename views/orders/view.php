<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y', $model->created_at),
            ],
            [
                'attribute' => 'await_date',
                'value' => date('d.m.Y', $model->await_date),
            ],
            'total_amount',
            [
                'attribute' => 'payment_type',
                'value' => $model->getPayCashLabel(),
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
                'value' => $model->getPaymentStatusLabel(),
            ],
            [
                'attribute' => 'delivery_status',
                'format' => 'raw',
                'value' => $model->getDeliveryStatusLabel(),
            ],
        ],
    ]) ?>
    <p class="pull-right">
        <?php if ($model->payment_status == $model::PAYMENT_INIT): ?>
            <?= Html::button('Оплачен частично', ['class' => 'btn btn-warning', 'data-toggle' => 'modal', 'data-target' => '#partPay']) ?>
        <?php endif ?>
        <?php if ($model->payment_status != $model::PAYMENT_FULL): ?>
            <?= Html::a('Оплачен полностью', ['full-pay', 'id' => $model->id], [
                'class' => 'btn btn-primary',
                'data' => [
                    'confirm' => 'Вы уверены, что клиент всё оплатил?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>
        <?php if ($model->payment_status == $model::PAYMENT_INIT and $model->delivery_status == $model::DELIVERY_INIT): ?>
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить заказ?',
                    'method' => 'post',
                ],
            ]) ?>
        <?php endif ?>
    </p>
    <br><br>
    <h2>Товары</h2>
    <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'action' => Url::toRoute(['orders/items-update', 'id' => $model->id]),
            'options' => ['class' => 'order_update_form']
        ]);
    ?>
    <table class="table">
        <tr>
            <th></th>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Размер</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th>Статус</th>
            <th>Кол-во</th>
        </tr>
        <?php foreach ($model->items as $order_item): ?>
            <tr>
                <td><?= $order_item->id ?></td>
                <td><?= $order_item->product->model->name ?></td>
                <td><?= $order_item->product->color->name ?></td>
                <td><?= $order_item->size->name ?></td>
                <td><?= $order_item->amount ?></td>
                <td><?= $order_item->price ?></td>
                <td>
                    <?php
                        echo Form::widget([
                            'formName' => 'order_items['.$order_item->id.']',
                            'columns' => 12,
                            'attributes' => [
                                'delivery_status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $order_item->getDeliveryStatuses(),
                                    'label' => false,
                                    'value' => $order_item->delivery_status,
                                ]
                            ],
                            'options' => ($order_item->delivery_status == $order_item::DELIVERY_FULL or !$model->accepted) ? ['disabled' => 'disabled'] : [],
                        ]);
                    ?>
                </td>
                <td>
                    <?php
                        echo Form::widget([
                            'formName' => 'order_items['.$order_item->id.']',
                            'columns' => 12,
                            'attributes' => [
                                'arrived' => [
                                    'type' => Form::INPUT_HTML5,
                                    'html5type' => 'number',
                                    'label' => false,
                                    'value' => $order_item->arrived,
                                    'options' => ($order_item->delivery_status == $order_item::DELIVERY_PART) ? ['type' => 'number'] : ['disabled' => 'disabled', 'type' => 'number'],
                                ]
                            ],
                        ]);
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr class="active">
            <th></th>
            <th>Дата ожидания</th>
            <th><?= date('d.m.Y', $model->await_date) ?></th>
            <th></th>
            <th></th>
            <th></th>
            <th>Итого</th>
            <th><?= $model->total_price ?></th>
        </tr>
    </table>

    <p>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?php ActiveForm::end(); ?>


    <!-- Modal -->
    <?php if ($model->payment_status == $model::PAYMENT_INIT): ?>
        <div class="modal fade" id="partPay" tabindex="-1" role="dialog" aria-labelledby="partPayLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="partPayLabel">Частичная оплата</h4>
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

</div>