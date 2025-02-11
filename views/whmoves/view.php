<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WHMove */

$this->title = 'Перемещение со склада в зал №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещения со склада в зал', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whmove-view">
    <h1><?= Html::encode($this->title) ?> <?= Html::a('Печать', 'javascript: print();', ['class' => 'btn btn-primary pull-right', 'title' => 'Печать']) ?></h1>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y', $model->created_at),
            ],
            'total_amount',
            [
                'attribute' => 'status',
                'format' => 'raw',
                'value' => $model->getStatusLabel(),
            ],
        ],
    ]) ?>
    <?php if ($model->status == $model::MOVE_INIT): ?>
        <p class="pull-right">
            <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Вы уверены, что хотите удалить перемещение?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif ?>
    <br><br>
    <h2>Товары</h2>
    <?php
        $form = ActiveForm::begin([
            'type'=>ActiveForm::TYPE_HORIZONTAL,
            'action' => Url::toRoute(['whmoves/items-update', 'id' => $model->id]),
            'options' => ['class' => 'whmove_update_form']
        ]);
    ?>
    <table class="table not_for_print">
        <tr>
            <th></th>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Размер</th>
            <th>Кол-во</th>
            <th>Статус</th>
            <th>Кол-во</th>
        </tr>
        <?php foreach ($model->items as $move_item): ?>
            <tr>
                <td><?= $move_item->id ?></td>
                <td><?= $move_item->product->model->name ?></td>
                <td><?= $move_item->product->color->name ?></td>
                <td><?= $move_item->size->name ?></td>
                <td><?= $move_item->amount ?></td>
                <td>
                    <?php
                        echo Form::widget([
                            'formName' => 'move_items['.$move_item->id.']',
                            'columns' => 12,
                            'attributes' => [
                                'status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $move_item->getStatuses(),
                                    'label' => false,
                                    'value' => $move_item->status,
                                ]
                            ],
                            'options' => ($move_item->status == $move_item::MOVE_FULL) ? ['disabled' => 'disabled'] : [],
                        ]);
                    ?>
                </td>
                <td>
                    <?php
                        echo Form::widget([
                            'formName' => 'move_items['.$move_item->id.']',
                            'columns' => 12,
                            'attributes' => [
                                'arrived' => [
                                    'type' => Form::INPUT_HTML5,
                                    'html5type' => 'number',
                                    'label' => false,
                                    'value' => $move_item->arrived,
                                    'options' => ($move_item->status == $move_item::MOVE_PART) ? ['type' => 'number'] : ['disabled' => 'disabled', 'type' => 'number'],
                                ]
                            ],
                        ]);
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>

    <table class="table for_print">
        <tr>
            <th></th>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Размер</th>
            <th>Кол-во</th>
        </tr>
        <?php foreach ($model->items as $move_item): ?>
            <tr>
                <td><?= $move_item->id ?></td>
                <td><?= $move_item->product->model->name ?></td>
                <td><?= $move_item->product->color->name ?></td>
                <td><?= $move_item->size->name ?></td>
                <td><?= $move_item->amount ?></td>
            </tr>
        <?php endforeach ?>
    </table>

    <p>
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success pull-right']) ?>
    </p>

    <?php ActiveForm::end(); ?>
</div>