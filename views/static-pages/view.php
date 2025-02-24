<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\StaticPage */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статичные страницы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="static-page-view">
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
    <?php if ($model->content): ?>
        <h3>Контент</h3>
        <div class="content" style="margin-bottom: 20px;">
            <?= $model->content ?>
        </div>
    <?php endif ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'filter_name',
            'alias',
            'h1',
            'title',
            'keywords',
            'description',
            [
                'attribute' => 'type',
                'value' => $model->typeName,
            ],
            [
                'attribute' => 'parent_id',
                'value' => ($model->parent) ? $model->parent->name : '-',
            ],
            [
                'attribute' => 'category_id',
                'value' => ($model->category) ? $model->category->name : '-',
            ],
            [
                'attribute' => 'fashion_id',
                'value' => ($model->fashion) ? $model->fashion->name : '-',
            ],
            [
                'attribute' => 'feature_id',
                'value' => ($model->feature) ? $model->feature->name : '-',
            ],
            [
                'attribute' => 'occasion_id',
                'value' => ($model->occasion) ? $model->occasion->name : '-',
            ],
            [
                'attribute' => 'color_id',
                'value' => ($model->color) ? $model->color->name : '-',
            ],
            [
                'attribute' => 'price_cat_id',
                'value' => ($model->priceCategory) ? $model->priceCategory->name : '-',
            ],
            'min_price',
            'max_price',
            'is_deleted',
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