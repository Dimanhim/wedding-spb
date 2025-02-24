<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>
<table class="table">
    <tr>
        <th></th>
        <th>марка</th>
        <th>продажа</th>
        <th></th>
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
            <td><?= $product->marka->name ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price, 0) ?></td>
            <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>зал</td>
        </tr>
        <tr>
            <td><?= $product->model->name ?></td>
            <td></td>
            <td class="empty"></td>
            <td class="empty"></td>
        </tr>
        <tr>
            <td><?= $product->color->name ?></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <td class="empty"></td>
            <td class="empty"></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="2" style="text-align: right; padding: 0 10px; line-height: 33px;">заказ</th>
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
            <th></th>
        </tr>
        <tr class="active">
            <th colspan="2" style="text-align: right; padding: 0 10px; line-height: 29px;">печать ШК</th>
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
        </tr>
    <?php endforeach ?>
    
</table>