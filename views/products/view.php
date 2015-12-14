<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Товар №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['index', 'category_id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Распечатать штрих-код', ['update', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
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
            [
                'attribute' => 'photo',
                'format' => 'raw',
                'value' => (file_exists(\Yii::$app->basePath.'/web'.$model->photo)) ? 
                    '<a href="'.$model->photo.'" class="fancybox">'.EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web'.$model->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND).'</a>' : 
                    EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web/files/no_photo.jpg',100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND),
            ],
            [
                'attribute' => 'purchase_price_small',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'purchase_price_big',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'purchase_price_small_dol',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'purchase_price_big_dol',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'recommended_price_small',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'recommended_price_big',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'price_small',
                'format'=> ['decimal', 0]
            ],
            [
                'attribute' => 'price_big',
                'format'=> ['decimal', 0]
            ],
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
    <h2>Таблица наличия</h2>
    <?= 
        $this->render('amount_table_'.$category->type, [
            'sizes' => $sizes,
            'category' => $category,
            'product' => $model
        ]);
    ?>
    <h2>Таблица продаж</h2>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> позиций</div>',
        'columns' => [
            'order_id',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
            [
                'attribute' => 'size_id',
                'value'=> function($data) {
                    return $data->size->name;
                }
            ],
            'amount',
            [
                'attribute' => 'price',
                'format'=> ['decimal', 0]
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', Url::toRoute(['receipts/view', 'id' => $model->order_id]),
                            ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                ],
            ],
        ],
    ]); ?>
</div>