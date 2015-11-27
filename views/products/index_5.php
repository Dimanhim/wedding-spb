<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>
<table class="table">
    <tr>
        <th></th>
        <th>$</th>
        <th>закупка</th>
        <th>закупка $</th>
        <th>реком.</th>
        <th>продажа</th>
        <th>2к</th>
        <th>3к</th>
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
            <td>&lt;48</td>
            <td><?= $product->purchase_price_small ?></td>
            <td><?= $product->purchase_price_small_dol ?></td>
            <td><?= $product->recommended_price_small ?></td>
            <td><?= $product->price_small ?></td>
            <td><?= isset($product->amounts[0]) ? $product->amounts[0]->amount : 0 ?></td>
            <td><?= isset($product->amounts[3]) ? $product->amounts[3]->amount : 0 ?></td>
            <td>зал</td>
            <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
        </tr>
        <tr>
            <td>&gt;50</td>
            <td><?= $product->purchase_price_big ?></td>
            <td><?= $product->purchase_price_big_dol ?></td>
            <td><?= $product->recommended_price_big ?></td>
            <td><?= $product->price_big ?></td>
            <td><?= isset($product->amounts[1]) ? $product->amounts[1]->amount : 0 ?></td>
            <td><?= isset($product->amounts[4]) ? $product->amounts[4]->amount : 0 ?></td>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td>12.06.15</td>
            <td></td>
            <td>2,0</td>
            <td>22.06.15</td>
            <td><?= isset($product->amounts[2]) ? $product->amounts[2]->amount : 0 ?></td>
            <td><?= isset($product->amounts[5]) ? $product->amounts[5]->amount : 0 ?></td>
            <td>ждем</td>
            <td><?= Html::a('Удалить', Url::toRoute(['products/delete', 'id' => $product->id]), [
                'class' => 'btn btn-danger btn-xs btn-block',
                'title' => 'Удалить',
                'data-confirm' => 'Вы уверены, что хотите удалить товар?',
            ]) ?></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="5" style="text-align: right; padding-top: 11px;">заказ</th>
            <td><input type="number" style="width: 40px;" min="0" max="99" value="0"></td>
            <td><input type="number" style="width: 40px;" min="0" max="99" value="0"></td>
            <th class="total_item_amount">0</th>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>