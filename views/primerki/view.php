<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Primerka */

$this->title = date('d.m.Y H:i', $model->date);
$this->params['breadcrumbs'][] = ['label' => 'Примерки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="primerka-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Режим планшета', 'http://www.wedding-spb.ru/site/login-as-client?primerka_id='.$model->id, ['class' => 'btn btn-success', 'target' => '_blank']) ?>
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
            [
                'attribute' => 'client_id',
                'format' => 'raw',
                'value' => $model->client ? Html::a($model->client->fio, ['clients/view', 'id' => $model->client_id]) : '-',
            ],
            [
                'label' => 'Телефон',
                'attribute' => 'client_id',
                'value' => $model->client ? $model->client->phone : '-',
            ],
            [
                'attribute' => 'date',
                'value' => date('d.m.Y H:i', $model->date),
            ],
            [
                'attribute' => 'wishes_field',
                'format' => 'raw',
                'value' => $model->formattedWishes,
            ],
            [
                'attribute' => 'products_field',
                'format' => 'raw',
                'value' => $model->formattedProducts,
            ],
            //'description:ntext',
            [
                'attribute' => 'result',
                'value' => $model->resultName,
            ],
            [
                'attribute' => 'receipt_id',
                'value' => $model->receipt ? Html::a('Чек №'.$model->receipt->id, ['receipts/view', 'id' => $model->receipt_id]) : '-',
            ],
            // [
            //     'attribute' => 'created_at',
            //     'value' => date('d.m.Y H:i', $model->created_at),
            // ],
            // [
            //     'attribute' => 'updated_at',
            //     'value' => date('d.m.Y H:i', $model->updated_at),
            // ],
        ],
    ]) ?>
</div>