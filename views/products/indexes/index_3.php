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
        <th>закупка</th>
        <th>закупка $</th>
        <th>реком.</th>
        <th>продажа</th>
        <?php foreach ($sizes as $size): ?>
            <th class="amount_th"><?= $size->name ?></th>
        <?php endforeach ?>
        <th>наличие</th>
        <th>действия</th>
    </tr>

    <?php foreach ($dataProvider->models as $product): ?>
        <?php
            $product_amounts = array_filter($amounts, function($item) use ($product) {
                return $item['product_id'] == $product->id;
            });
            $mark_key = array_search($product->marka_id, array_column($marks, 'id'));
            $color_key = array_search($product->color_id, array_column($colors, 'id'));
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
            <td>&lt;48</td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_small, 0) ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = '';
                    foreach ($product_amounts as $amount) {
                        if ($amount['size_id'] == $size->id and $amount['amount_type'] == 0) {
                            $amount_size = $amount;
                        }
                    }
                    echo $amount_size ? '<td class="amount amount_'.$amount_size['amount'].'">'.$amount_size['amount'].'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td>зал</td>
            <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
        </tr>
        <tr>
            <td><?= $product->model->name ?></td>
            <td>&gt;50</td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_big, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_big_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price_big, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_big, 0) ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = '';
                    foreach ($product_amounts as $amount) {
                        if ($amount['size_id'] == $size->id and $amount['amount_type'] == 1) {
                            $amount_size = $amount;
                        }
                    }
                    echo $amount_size ? '<td class="amount amount_'.$amount_size['amount'].'">'.$amount_size['amount'].'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td><?= $colors[$color_key]->name //$product->color->name ?></td>
            <td></td>
            <td><?= $product->purchase_date ? date('d.m.Y', $product->purchase_date) : '-' ?></td>
            <td></td>
            <td><?= $rates[$rate_key]['name'] //$product->ratio->name ?></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = '';
                    foreach ($product_amounts as $amount) {
                        if ($amount['size_id'] == $size->id and $amount['amount_type'] == 2) {
                            $amount_size = $amount;
                        }
                    }
                    echo $amount_size ? '<td class="amount amount_'.$amount_size['amount'].'">'.$amount_size['amount'].'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
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
            <td></td>
        </tr>
        <tr class="active">
            <th colspan="6" style="text-align: right; padding: 0 10px; line-height: 29px;">печать ШК</th>
            <?php foreach ($sizes as $size): ?>
                <td class="amount_inp">
                    <input type="checkbox" name="sh_print[]" data-barcode="<?= $product->getBarcode($product, $size->id) ?>" style="width: 40px;" >
                </td>
            <?php endforeach ?>
            <td></td>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>