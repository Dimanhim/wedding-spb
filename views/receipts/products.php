<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ReceiptItem;
use app\models\Receipt;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Проданные товары';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="search_block">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
        <?= Html::a('Печать', 'javascript:print()', ['class' => 'btn btn-default']) ?>
        <?= $this->render('_search_products', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> товаров</div>',
        'showFooter' => true,
        'columns' => [
            'id',
            'receipt_id',
            [
                'attribute' => 'product_id',
                'label' => 'Марка',
                'value'=> function($data) {
                    return $data->marka_name ? $data->marka_name : '-';
                }
            ],
            [
                'attribute' => 'product_id',
                'label' => 'Модель',
                'value'=> function($data) {
                    return $data->model_name ? $data->model_name : '-';
                }
            ],
            [
                'attribute' => 'product_id',
                'label' => 'Цвет',
                'value'=> function($data) {
                    return $data->color_name ? $data->color_name : '-';
                }
            ],
            [
                'attribute' => 'product_id',
                'label' => 'Р-р',
                'value'=> function($data) use ($sizes) {
                    $size_key = array_search($data->size_id, array_column($sizes, 'id'));
                    return ($size_key !== false) ? $sizes[$size_key]['name'] : '-';
                }
            ],
            [
                'attribute' => 'manager_id',
                'value'=> function($data) use ($managers) {
                    $manager_key = array_search($data->manager_id, array_column($managers, 'id'));
                    return ($manager_key !== false) ? $managers[$manager_key]['surname'].' '.$managers[$manager_key]['name'] : '-';
                },
                'footer' => 'Итого:',
            ],
            [
                'attribute' => 'price',
                'format'=> ['decimal', 0],
                'footer' => ReceiptItem::pageTotal($dataProvider->models, 'price'),
            ],
            [
                'attribute' => 'purchase_price',
                'format'=> ['decimal', 0],
                'value'=> function($data) {
                    $purchase_price = 0;
                    if ($data->size_id) {
                        $size_key = array_search($data->size_id, array_column($sizes, 'id'));
                        if ($sizes[$size_key]['name'] < 50 and $data->purchase_price_small) {
                            $purchase_price += $data->purchase_price_small;
                        }
                        if ($sizes[$size_key]['name'] >= 50 and $data->purchase_price_big) {
                            $purchase_price += $data->purchase_price_big;
                        }
                    }
                    $purchase_price += $data->purchase_price;
                    return $purchase_price;
                },
                'footer' => ReceiptItem::purchaseTotal($dataProvider->models, $sizes),
                'visible' => (Yii::$app->user->identity->username == 'sale') ? 0 : 1,
            ],
            [
                'attribute' => 'amount',
                'format'=> ['decimal', 0],
                'footer' => ReceiptItem::pageTotal($dataProvider->models, 'amount'),
            ],
            [
                'attribute' => 'sale',
                'format'=> ['decimal', 0],
                'footer' => ReceiptItem::pageTotal($dataProvider->models, 'sale'),
            ],
            [
                'attribute' => 'gift',
                'format'=> ['boolean'],
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0],
                'footer' => ReceiptItem::pageTotal($dataProvider->models, 'total_price'),
            ],
        ],
    ]); ?>
</div>