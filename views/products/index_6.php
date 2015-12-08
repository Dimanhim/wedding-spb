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
        <th></th>
        <th>наличие</th>
        <th>действия</th>
    </tr>

    <?php foreach ($dataProvider->getModels() as $product): ?>
        <?php
            $amounts = $product->amounts;
        ?>
        <tr>
            <td rowspan="4">
                <?php if (file_exists(\Yii::$app->basePath.'/web'.$product->photo)): ?>
                    <a href="<?= $product->photo ?>" class="fancybox">
                        <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web'.$product->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                    </a>
                <?php else: ?>
                    <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/web/files/no_photo.jpg',100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
                <?php endif ?>
            </td>
            <td><?= $product->marka->name ?></td>
            <td>&lt;48</td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_small_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price_small, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_small, 0) ?></td>
            <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>зал</td>
            <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td>&gt;50</td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_big, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_big_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price_big, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price_big, 0) ?></td>
            <?= isset($product->amounts[1]) ? '<td class="amount amount_'.$product->amounts[1]->amount.'">'.$product->amounts[1]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>12.06.15</td>
            <td></td>
            <td>2,0</td>
            <td>22.06.15</td>
            <?= isset($product->amounts[2]) ? '<td class="amount amount_'.$product->amounts[2]->amount.'">'.$product->amounts[2]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>ждем</td>
            <td><?= Html::a('Удалить', Url::toRoute(['products/delete', 'id' => $product->id]), [
                'class' => 'btn btn-danger btn-xs btn-block',
                'title' => 'Удалить',
                'data-confirm' => 'Вы уверены, что хотите удалить товар?',
            ]) ?></td>
        </tr>
        <tr class="active order_tr">
            <th colspan="6" style="text-align: right; padding-top: 11px;">заказ</th>
            <td class="amount_inp"><input type="number" style="width: 40px;" min="0" max="99" value="0"></td>
            <th class="total_item_amount">0</th>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>