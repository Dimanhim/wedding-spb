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
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->purchase_price_dol, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->recommended_price, 0) ?></td>
            <td><?= Yii::$app->formatter->asDecimal($product->price, 0) ?></td>
            <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>зал</td>
            <td><?= Html::a('Копировать', Url::toRoute(['products/copy', 'id' => $product->id]), ['class' => 'btn btn-success btn-xs btn-block', 'title' => 'Копировать']) ?></td>
        </tr>
        <tr>
            <td><?= $product->model->name ?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <?= isset($product->amounts[1]) ? '<td class="amount amount_'.$product->amounts[1]->amount.'">'.$product->amounts[1]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <td>склад</td>
            <td><?= Html::a('Редактировать', Url::toRoute(['products/update', 'id' => $product->id]), ['class' => 'btn btn-primary btn-xs btn-block', 'title' => 'Редактировать']) ?></td>
        </tr>
        <tr>
            <td><?= $product->color->name ?></td>
            <td><?= $product->purchase_date ? date('d.m.Y', $product->purchase_date) : '-' ?></td>
            <td></td>
            <td><?= $product->ratio->name ?></td>
            <td><?= $product->sell_date ? date('d.m.Y', $product->sell_date) : '-' ?></td>
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
            <td class="amount_inp">
                <input type="number" name="items[]" data-product="<?= $product->id ?>" style="width: 40px;" 
                data-price="<?= $product->price ?>" min="0" max="99" value="0">
            </td>
            <th class="total_item_amount">0</th>
            <td></td>
        </tr>
    <?php endforeach ?>
    
</table>