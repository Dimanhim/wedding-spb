<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;

?>
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
            <th class="amount_th"><?= $size->name ?></th>
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
                <a href="<?= Url::toRoute(['products/view', 'id' => $product->id]) ?>">
                    <?php if (file_exists(\Yii::$app->basePath.'/web'.$product->photo)): ?>
                        <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web'.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                    <?php else: ?>
                        <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web/files/no_photo.jpg',100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                    <?php endif ?>
                </a>
            </td>
            <td><?= $product->marka->name ?></td>
            <td>&lt;48</td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_small, 0) ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
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
                    $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 1])->one();
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td><?= $product->color->name ?></td>
            <td></td>
            <td><?= $product->purchase_date ? date('d.m.Y', $product->purchase_date) : '-' ?></td>
            <td></td>
            <td><?= $product->ratio->name ?></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 2])->one();
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td>ждем</td>
            <td><?= Html::a('Удалить', Url::toRoute(['products/delete', 'id' => $product->id]), [
                'class' => 'btn btn-danger btn-xs btn-block',
                'title' => 'Удалить',
                'data-confirm' => 'Вы уверены, что хотите удалить товар?',
            ]) ?></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="6" style="text-align: right; padding-top: 11px;">заказ</th>
            <?php foreach ($sizes as $size): ?>
                <td class="amount_inp">
                    <input type="number" name="items[]" data-product="<?= $product->id ?>" data-size="<?= $size->id ?>" style="width: 40px;" 
                    data-price="<?= ($size->name <= 50) ? $product->price_small : $product->price_big; ?>" min="0" max="99" value="0">
                </td>
            <?php endforeach ?>
            <th class="total_item_amount">0</th>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>