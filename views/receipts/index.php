<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Receipt;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Чеки';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="receipt-index">
    <h1><?= Html::encode($this->title) ?></h1>
    
    <div class="search_block">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
        <?= Html::a('Добавить чек', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Печать', 'javascript:print()', ['class' => 'btn btn-default']) ?>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> чеков</div>',
        'showFooter' => true,
        'columns' => [
            'id',
            [
                'attribute' => 'manager_id',
                'value'=> function($data) use ($managers) {
                    $manager_key = array_search($data->manager_id, array_column($managers, 'id'));
                    return ($manager_key !== false) ? $managers[$manager_key]['surname'].' '.$managers[$manager_key]['name'] : '-';
                },
            ],
            [
                'attribute' => 'payment_type',
                'value'=> function($data) {
                    return $data->getPayCashLabel();
                }
            ],
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->created_at);
                },
                'footer' => 'Итого:',
            ],
            [
                'attribute' => 'total_amount',
                'footer' => Receipt::pageTotal($dataProvider->models, 'total_amount'),
            ],
            [
                'attribute' => 'cash_total',
                'format'=> ['decimal', 0],
                'footer' => Receipt::pageTotal($dataProvider->models, 'cash_total'),
            ],
            [
                'attribute' => 'nocash_total',
                'format'=> ['decimal', 0],
                'footer' => Receipt::pageTotal($dataProvider->models, 'nocash_total'),
            ],
            [
                'attribute' => 'purchase_price',
                'format'=> ['decimal', 0],
                'value'=> function($data) use ($itemsExt) {
                    $purchase_price = 0;
                    $reciept_items = array_filter($itemsExt, function($item) use ($data) {
                        return $item['receipt_id'] == $data->id;
                    });
                    foreach ($reciept_items as $reciept_item) {
                        if ($reciept_item['size_id']) {
                            $size_key = array_search($reciept_item['size_id'], array_column($sizes, 'id'));
                            if ($sizes[$size_key]['name'] < 50 and $reciept_item['purchase_price_small']) {
                                $purchase_price += $reciept_item['purchase_price_small'];
                            }
                            if ($sizes[$size_key]['name'] >= 50 and $reciept_item['purchase_price_big']) {
                                $purchase_price += $reciept_item['purchase_price_big'];
                            }
                        }
                        $purchase_price += $reciept_item['purchase_price'];
                    }
                    return $purchase_price;
                },
                'footer' => Receipt::purchaseTotal($dataProvider->models, $sizes, $itemsExt),
                'visible' => (Yii::$app->user->identity->username == 'sale') ? 0 : 1,
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0],
                'footer' => Receipt::pageTotal($dataProvider->models, 'total_price'),
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view}{delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return (Yii::$app->user->identity->username == 'admin') ? Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить чек?',
                                'method' => 'post',
                            ],
                        ]) : '';
                    },
                ],
            ],
        ],
    ]); ?>
</div>