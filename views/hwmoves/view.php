<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\HWMove */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещения из зала на склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hwmove-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить перемещение?',
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
            <th>Статус</th>
            <th>Кол-во</th>
        </tr>
        <?php foreach ($model->items as $key => $move_item): ?>
            <tr>
                <td><?= $key ?></td>
                <td><?= $move_item->product->model->name ?></td>
                <td><?= $move_item->product->color->name ?></td>
                <td><?= $move_item->size->name ?></td>
                <td><?= $move_item->amount ?></td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['orders/change-item-status', 'id' => $move_item->id]),
                            'options' => ['class' => 'order_item_status_form']
                        ]);
                        echo Form::widget([
                            'model' => $move_item,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $move_item->getStatuses(),
                                    'label' => false,
                                ]
                            ],
                            'options' => ($move_item->status == $move_item::STATUS_CANCELED or $move_item->status == $move_item::STATUS_ACTIVE) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type' => ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['orders/change-item-status', 'id' => $move_item->id]),
                            'options' => ['class' => 'order_item_arrived_form']
                        ]);
                        echo Form::widget([
                            'model' => $move_item,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'arrived' => [
                                    'type' => Form::INPUT_HTML5,
                                    'html5type' => 'number',
                                    'label' => false,
                                ]
                            ],
                            'options' => ($move_item->status == $move_item::STATUS_PART_COME) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
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