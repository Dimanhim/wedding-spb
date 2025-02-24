<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>

<table class="table">
    <tr>
        <th></th>
        <th>марка</th>
        <th>закупка</th>
        <th>закупка $</th>
        <th>реком.</th>
        <th>продажа</th>
        <th></th>
        <th>наличие</th>
        <th>действия</th>
    </tr>

    <?php foreach ($dataProvider->models as $product): ?>
        <?php
            $product_amounts = array_filter($amounts, function($item) use ($product) {
                return $item['product_id'] == $product->id;
            });
            $product_amounts1 = array_values(array_filter($product_amounts, function($item) {
                return $item['amount_type'] == 0;
            }));
            $product_amounts2 = array_values(array_filter($product_amounts, function($item) {
                return $item['amount_type'] == 1;
            }));
            $product_amounts3 = array_values(array_filter($product_amounts, function($item) {
                return $item['amount_type'] == 2;
            }));
            $mark_key = array_search($product->marka_id, array_column($marks, 'id'));
            $rate_key = array_search($product->ratio_id, array_column($rates, 'id'));
            //$amounts = $product->amounts;
        ?>
        <tr>
            <td rowspan="5">
                <a href="<?= Url::toRoute(['products/view', 'id' => $product->id]) ?>">
                    <?php if ($product->photo and file_exists(\Yii::$app->basePath.'/public_html'.$product->photo)): ?>
                        <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                    <?php else: ?>
                        <img src="/files/no_image.png" width="100" alt="">
                    <?php endif ?>
                </a>
            </td>
            <td><?= $marks[$mark_key]['name'] //$product->marka->name ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price, 0) ?></td>
            <?= isset($product_amounts1[0]) ? '<td class="amount amount_'.$product_amounts1[0]['amount'].'">'.$product_amounts1[0]['amount'].'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>зал</td>
            <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?= isset($product_amounts2[0]) ? '<td class="amount amount_'.$product_amounts2[0]['amount'].'">'.$product_amounts2[0]['amount'].'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $product->purchase_date ? date('d.m.Y', $product->purchase_date) : '-' ?></td>
            <td></td>
            <td><?= $rates[$rate_key]['name'] //$product->ratio->name ?></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <?= isset($product_amounts3[0]) ? '<td class="amount amount_'.$product_amounts3[0]['amount'].'">'.$product_amounts3[0]['amount'].'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>ждем</td>
            <?php if ($product->is_deleted): ?>
                <td><?= Html::a('Восстановить', Url::toRoute(['products/restore', 'id' => $product->id]), [
                    'class' => 'btn btn-warning btn-xs btn-block',
                    'title' => 'Восстановить',
                    'data-confirm' => 'Вы уверены, что хотите восстановить товар?',
                ]) ?></td>
            <?php else: ?>
                <td><?= Html::a('Удалить', Url::toRoute(['products/delete', 'id' => $product->id]), [
                    'class' => 'btn btn-danger btn-xs btn-block',
                    'title' => 'Удалить',
                    'data-confirm' => 'Вы уверены, что хотите удалить товар?',
                ]) ?></td>
            <?php endif ?>
        </tr>
        <tr class="active order_tr">
            <th colspan="6" style="text-align: right; padding: 0 10px; line-height: 33px;">заказ</th>
            <?php if ($product->purchase_price): ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>" style="width: 40px;" 
                    data-price="<?= $product->purchase_price ?>" min="0" max="99" value="0">
                </td>
            <?php else: ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>" data-size="<?= $size->id ?>" style="width: 40px;" 
                        data-price="0" min="0" max="99" value="0">
                </td>
            <?php endif ?>
            <th class="total_item_amount">0</th>
            <td></td>
        </tr>
        <tr class="active">
            <th colspan="6" style="text-align: right; padding: 0 10px; line-height: 29px;">печать ШК</th>
            <?php if ($product->purchase_price): ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product) ?>" style="width: 40px;" >
                </td>
            <?php else: ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product, $size->id) ?>" style="width: 40px;" >
                </td>
            <?php endif ?>
            <td></td>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>