<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Заказы';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table">
        <tr>
            <th>№</th>
            <th>Дата заказа</th>
            <th>Дата ожидания</th>
            <th>Кол-во</th>
            <th>Оплата</th>
            <th>Сумма</th>
            <th>Оплачено</th>
            <th>Остаток</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($dataProvider->getModels() as $order): ?>
            <tr>
                <td><?= $order->id ?></td>
                <td><?= date('d.m.Y H:i', $order->created_at) ?></td>
                <td><?= date('d.m.Y', $order->await_date) ?></td>
                <td><?= $order->total_amount ?></td>
                <td><?= $order->payment ?></td>
                <td><?= $order->total_price ?></td>
                <td><?= $order->total_payed ?></td>
                <td><?= $order->total_rest ?></td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['orders/change-status', 'id' => $order->id]),
                            'options' => ['class' => 'order_status_form']
                        ]);
                        echo Form::widget([
                            'model' => $order,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $order->getStatuses(),
                                    'label' => false,
                                ]
                            ],
                            'options' => ($order->status == $order::STATUS_CANCELED or $order->status == $order::STATUS_ACTIVE) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['orders/view', 'id' => $order->id]), ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']); ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['orders/update', 'id' => $order->id]), ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']); ?>
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['orders/delete', 'id' => $order->id]), [
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Удалить',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить заказ?',
                            'method' => 'post',
                        ],
                    ]); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>