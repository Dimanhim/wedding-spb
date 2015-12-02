<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Order */

$this->title = 'Заказ №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Заказы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="order-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить заказ?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    
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
        <?php foreach ($model->items as $key => $order_item): ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $order_item->product->model->name ?></td>
                <td><?= $order_item->product->color->name ?></td>
                <td><?= $order_item->size->name ?></td>
                <td><?= $order_item->amount ?></td>
                <td><?= $order_item->price ?></td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['orders/change-item-status', 'id' => $order_item->id]),
                            'options' => ['class' => 'order_item_status_form']
                        ]);
                        echo Form::widget([
                            'model' => $order_item,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $order_item->getStatuses(),
                                    'label' => false,
                                ]
                            ],
                            'options' => ($order_item->status == $order_item::STATUS_CANCELED or $order_item->status == $order_item::STATUS_ACTIVE) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type' => ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['orders/change-item-status', 'id' => $order_item->id]),
                            'options' => ['class' => 'order_item_arrived_form']
                        ]);
                        echo Form::widget([
                            'model' => $order_item,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'arrived' => [
                                    'type' => Form::INPUT_HTML5,
                                    'html5type' => 'number',
                                    'label' => false,
                                ]
                            ],
                            'options' => ($order_item->status == $order_item::STATUS_PART_COME) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
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
    
    <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'action' => Url::toRoute(['orders/change-status', 'id' => $model->id]),
            'options' => ['class' => 'order_status_form']
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'status' => [
                    'type' => Form::INPUT_DROPDOWN_LIST, 
                    'items'=> $model->getStatuses(),
                ]
            ],
            'options' => ($model->status == $model::STATUS_CANCELED or $model->status == $model::STATUS_ACTIVE) ? [] : ['disabled' => 'disabled'],
        ]);
        ActiveForm::end();
    ?>

</div>