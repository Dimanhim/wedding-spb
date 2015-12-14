<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Операции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотеите удалить операцию?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'user_id',
            [
                'attribute' => 'type_id',
                'value' => $model->getTypeLabel(),
            ],
            [
                'attribute' => 'cat_id',
                'value' => $model->getCatLabel(),
            ],
            [
                'attribute' => 'payment_type',
                'value' => $model->getPayLabel(),
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'repeated',
                'value' => $model->repeated ? 'да' : 'нет',
            ],
            'interval',
            'months',
            'days',
            'week',
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

</div>
