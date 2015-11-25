<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Товары', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить товар?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
                'attribute' => 'marka_id',
                'value' => $model->marka->name,
            ],
            [
                'attribute' => 'model_id',
                'value' => $model->model->name,
            ],
            [
                'attribute' => 'color_id',
                'value' => $model->color->name,
            ],
            'description',
            'photo',
            'purchase_price_small',
            'purchase_price_big',
            'purchase_price_small_dol',
            'purchase_price_big_dol',
            'recommended_price_small',
            'recommended_price_big',
            'price_small',
            'price_big',
            [
                'attribute' => 'ratio_id',
                'value' => $model->ratio->name,
            ],
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