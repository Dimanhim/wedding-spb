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

    <table class="table">
        <tr>
            <th></th>
            <th>марка</th>
            <th>$</th>
            <th>закупка</th>
            <th>закупка $</th>
            <th>реком.</th>
            <th>продажа</th>
            <?php foreach ($sizes as $size): ?>
                <th><?= $size->name ?></th>
            <?php endforeach ?>
            <th>наличие</th>
            <th>действия</th>
        </tr>

        <?php foreach ($dataProvider->getModels() as $product): ?>
            <?php
                $amounts = $product->amounts;
            ?>
            <tr>
                <td rowspan="4">
                    <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                </td>
                <td><?= $product->marka->name ?></td>
                <td>&lt;48</td>
                <td><?= $product->purchase_price_small ?></td>
                <td><?= $product->purchase_price_small_dol ?></td>
                <td><?= $product->recommended_price_small ?></td>
                <td><?= $product->price_small ?></td>
                <?php foreach ($sizes as $size): ?>
                    <?php $amount_key = $searchModel::multi_array_search($amounts, ['size_id' => $size->id, 'amount_type' => 1]); ?>
                    <td><?= ($amount_key === false) ? 0 : $amounts[$amount_key]['amount'] ?></td>
                <?php endforeach ?>
                <td>зал</td>
                <td>копировать</td>
            </tr>
            <tr>
                <td><?= $product->model->name ?></td>
                <td>&gt;50</td>
                <td><?= $product->purchase_price_big ?></td>
                <td><?= $product->purchase_price_big_dol ?></td>
                <td><?= $product->recommended_price_big ?></td>
                <td><?= $product->price_big ?></td>
                <?php foreach ($sizes as $size): ?>
                    <?php $amount_key = $searchModel::multi_array_search($amounts, ['size_id' => $size->id, 'amount_type' => 2]); ?>
                    <td><?= ($amount_key === false) ? 0 : $amounts[$amount_key]['amount'] ?></td>
                <?php endforeach ?>
                <td>склад</td>
                <td>удалить</td>
            </tr>
            <tr>
                <td><?= $product->color->name ?></td>
                <td></td>
                <td>12.06.15</td>
                <td></td>
                <td>2,0</td>
                <td>22.06.15</td>
                <?php foreach ($sizes as $size): ?>
                    <?php $amount_key = $searchModel::multi_array_search($amounts, ['size_id' => $size->id, 'amount_type' => 3]); ?>
                    <td><?= ($amount_key === false) ? 0 : $amounts[$amount_key]['amount'] ?></td>
                <?php endforeach ?>
                <td>ждем</td>
                <td>редактировать</td>
            </tr>
            <tr class="active">
                <td colspan="6">заказ</td>
                <?php foreach ($sizes as $size): ?>
                    <td><input type="text" style="width: 30px;"></td>
                <?php endforeach ?>
                <td>2</td>
                <td></td>
            </tr>
        <?php endforeach ?>
        
    </table>
</div>