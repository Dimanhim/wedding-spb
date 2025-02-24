<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>
<table class="table">
    <tr>
        <th></th>
        <th>Р-р</th>
        <th>продажа</th>
        <th class="amount_th">2к</th>
        <th class="amount_th">3к</th>
        <th>наличие</th>
    </tr>

    <?php foreach ($dataProvider->getModels() as $product): ?>
        <tr>
            <td rowspan="5">
                <?php if ($product->photo and file_exists(\Yii::$app->basePath.'/public_html'.$product->photo)): ?>
                    <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                <?php else: ?>
                    <img src="/files/no_image.png" width="100" alt="">
                <?php endif ?>
            </td>
            <td>2к</td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_small, 0) ?></td>
            <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <?= isset($product->amounts[3]) ? '<td class="amount amount_'.$product->amounts[3]->amount.'">'.$product->amounts[3]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>зал</td>
        </tr>
        <tr>
            <td>3к</td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_big, 0) ?></td>
            <td class="empty"></td>
            <td class="empty"></td>
            <td class="empty"></td>
        </tr>
        <tr>
            <td></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <td class="empty"></td>
            <td class="empty"></td>
            <td class="empty"></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="3" style="text-align: right; padding: 0 10px; line-height: 33px;">заказ</th>
            <?php if ($product->purchase_price_small): ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>" style="width: 40px;" 
                    data-price="<?= $product->purchase_price_small ?>" min="0" max="99" value="0">
                </td>
            <?php else: ?>
                <td class="amount_inp">
                    <input type="number" disabled="disabled" style="width: 40px;" value="0">
                </td>
            <?php endif ?>
            <?php if ($product->purchase_price_big): ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>" style="width: 40px;" 
                    data-price="<?= $product->purchase_price_big ?>" min="0" max="99" value="0">
                </td>
            <?php else: ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>"  style="width: 40px;" 
                        data-price="0" min="0" max="99" value="0">
                </td>
            <?php endif ?>
            <th class="total_item_amount">0</th>
        </tr>
        <tr class="active">
            <th colspan="3" style="text-align: right; padding: 0 10px; line-height: 29px;">печать ШК</th>
            <?php if ($product->purchase_price_small): ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product) ?>" style="width: 40px;" >
                </td>
            <?php endif ?>
            <?php if ($product->purchase_price_big): ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product) ?>" style="width: 40px;" >
                </td>
            <?php else: ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]"  style="width: 40px;" >
                </td>
            <?php endif ?>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>