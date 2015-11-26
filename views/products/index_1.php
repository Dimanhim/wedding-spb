<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

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
                    <td>
                        <?php
                            $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                            echo $amount_size ? $amount_size->amount : 0;
                        ?>
                    </td>
                <?php endforeach ?>
                <td>зал</td>
                <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
            </tr>
            <tr>
                <td><?= $product->model->name ?></td>
                <td>&gt;50</td>
                <td><?= $product->purchase_price_big ?></td>
                <td><?= $product->purchase_price_big_dol ?></td>
                <td><?= $product->recommended_price_big ?></td>
                <td><?= $product->price_big ?></td>
                <?php foreach ($sizes as $size): ?>
                    <td>
                        <?php
                            $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 1])->one();
                            echo $amount_size ? $amount_size->amount : 0;
                        ?>
                    </td>
                <?php endforeach ?>
                <td>склад</td>
                <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
            </tr>
            <tr>
                <td><?= $product->color->name ?></td>
                <td></td>
                <td>12.06.15</td>
                <td></td>
                <td>2,0</td>
                <td>22.06.15</td>
                <?php foreach ($sizes as $size): ?>
                    <td>
                        <?php
                            $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 2])->one();
                            echo $amount_size ? $amount_size->amount : 0;
                        ?>
                    </td>
                <?php endforeach ?>
                <td>ждем</td>
                <td><?= Html::a('Удалить', Url::toRoute(['products/delete', 'id' => $product->id]), [
                    'class' => 'btn btn-danger btn-xs btn-block',
                    'title' => 'Удалить',
                    'data-confirm' => 'Вы уверены, что хотите удалить товар?',
                ]) ?></td>
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