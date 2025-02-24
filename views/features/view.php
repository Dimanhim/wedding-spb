<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Feature */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Особенности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="feature-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить особенность?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
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