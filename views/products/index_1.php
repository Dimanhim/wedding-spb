<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Добавить товар', ['create', 'category_id' => $category->id], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> товаров</div>',
        'columns' => [
            [
                'attribute' => 'photo',
                'enableSorting' => false,
                'format' => 'raw',
                'value'=> function($data) {
                    return EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.$data->photo, 150, 100);
                },
                'groupedRow'=>true,
            ],
            [
                'attribute' => 'marka_id', 
                'value'=> function ($model) { 
                    return $model->marka->name;
                },
                'group' => true,
                'subGroupOf' => 1,
            ],
            'model_id',
            'color_id',
            'description',
            // 'photo',
            // 'purchase_price_small',
            // 'purchase_price_big',
            // 'purchase_price_small_dol',
            // 'purchase_price_big_dol',
            // 'recommended_price_small',
            // 'recommended_price_big',
            // 'price_small',
            // 'price_big',
            // 'price_ratio',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->created_at);
                }
            ],
            ['class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить товар?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>