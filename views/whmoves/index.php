<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Перемещения со склада в зал';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="whmove-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <table class="table">
        <tr>
            <th>№</th>
            <th>Дата перемещения</th>
            <th>Кол-во</th>
            <th>Статус</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($dataProvider->getModels() as $move): ?>
            <tr>
                <td><?= $move->id ?></td>
                <td><?= date('d.m.Y H:i', $move->created_at) ?></td>
                <td><?= $move->total_amount ?></td>
                <td>
                    <?php
                        $form = ActiveForm::begin([
                            'type'=>ActiveForm::TYPE_HORIZONTAL,
                            'action' => Url::toRoute(['whmoves/change-status', 'id' => $move->id]),
                            'options' => ['class' => 'whmoves_status_form']
                        ]);
                        echo Form::widget([
                            'model' => $move,
                            'form' => $form,
                            'columns' => 12,
                            'attributes' => [
                                'status' => [
                                    'type' => Form::INPUT_DROPDOWN_LIST, 
                                    'items'=> $move->getStatuses(),
                                    'label' => false,
                                ]
                            ],
                            'options' => ($move->status == $move::STATUS_CANCELED or $move->status == $move::STATUS_ACTIVE) ? [] : ['disabled' => 'disabled'],
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
                <td>
                    <?= Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['whmoves/view', 'id' => $move->id]), ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']); ?>
                    <?= Html::a('<span class="glyphicon glyphicon-pencil"></span>', Url::toRoute(['whmoves/update', 'id' => $move->id]), ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']); ?>
                    <?= Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['whmoves/delete', 'id' => $move->id]), [
                        'class' => 'btn btn-danger btn-xs',
                        'title' => 'Удалить',
                        'data' => [
                            'confirm' => 'Вы уверены, что хотите удалить перемещение?',
                            'method' => 'post',
                        ],
                    ]); ?>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>