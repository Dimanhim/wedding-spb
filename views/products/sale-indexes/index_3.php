<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>
<table class="table">
    <tr>
        <th></th>
        <th>марка</th>
        <th>Р-р</th>
        <th>продажа</th>
        <?php foreach ($sizes as $size): ?>
            <th class="amount_th"><?= $size->name ?></th>
        <?php endforeach ?>
        <th>наличие</th>
    </tr>

    <?php foreach ($dataProvider->getModels() as $product): ?>
        <?php $amounts = $product->amounts; ?>
        <tr>
            <td rowspan="5">
                <?php if ($product->photo and file_exists(\Yii::$app->basePath.'/public_html'.$product->photo)): ?>
                    <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                <?php else: ?>
                    <img src="/files/no_image.png" width="100" alt="">
                <?php endif ?>
            </td>
            <td><?= $product->marka->name ?></td>
            <td>&lt;48</td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_small, 0) ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = '';
                    foreach ($amounts as $amount) {
                        if ($amount->size_id == $size->id and $amount->amount_type == 0) {
                            $amount_size = $amount;
                        }
                    }
                    //$amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td>зал</td>
        </tr>
        <tr>
            <td><?= $product->model->name ?></td>
            <td>&gt;50</td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_big, 0) ?></td>
            <?php foreach ($sizes as $size): ?>
                <td class="empty"></td>
            <?php endforeach ?>
            <td class="empty"></td>
        </tr>
        <tr>
            <td><?= $product->color->name ?></td>
            <td></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <?php foreach ($sizes as $size): ?>
                <td class="empty"></td>
            <?php endforeach ?>
            <td class="empty"></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="3" style="text-align: right; padding: 0 10px; line-height: 33px;">заказ</th>
            <?php foreach ($sizes as $size): ?>
                <?php if ($size->name < 50 and $product->purchase_price_small): ?>
                    <td class="amount_inp">
                        <input type="number" name="items[]" data-product="<?= $product->id ?>" data-size="<?= $size->id ?>" style="width: 40px;" 
                        data-price="<?= $product->purchase_price_small ?>" min="0" max="99" value="0">
                    </td>
                <?php elseif ($size->name >= 50 and $product->purchase_price_big): ?>
                    <td class="amount_inp">
                        <input type="number" name="items[]" data-product="<?= $product->id ?>" data-size="<?= $size->id ?>" style="width: 40px;" 
                        data-price="<?= $product->purchase_price_big ?>" min="0" max="99" value="0">
                    </td>
                <?php else: ?>
                    <td class="amount_inp">
                        <input type="number" name="items[]" data-product="<?= $product->id ?>" data-size="<?= $size->id ?>" style="width: 40px;" 
                        data-price="0" min="0" max="99" value="0">
                    </td>
                <?php endif ?>
            <?php endforeach ?>
            <th class="total_item_amount">0</th>
        </tr>
        <tr class="active">
            <th colspan="3" style="text-align: right; padding: 0 10px; line-height: 29px;">печать ШК</th>
            <?php foreach ($sizes as $size): ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product, $size->id) ?>" style="width: 40px;" >
                </td>
            <?php endforeach ?>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>