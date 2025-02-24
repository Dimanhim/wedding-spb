<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Blacklist */

$this->title = $model->ip;
$this->params['breadcrumbs'][] = ['label' => 'Блокированные IP', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="blacklist-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'ip',
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