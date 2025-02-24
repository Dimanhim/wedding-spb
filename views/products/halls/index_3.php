<?php

use yii\helpers\Html;

?>

<table class="table">
    <tr class="info">
        <th>марка</th>
        <th>модель</th>
        <th>цвет</th>
        <?php foreach ($sizes as $size): ?>
            <th class="amount_th"><?= $size->name ?></th>
        <?php endforeach ?>
    </tr>

    <?php foreach ($dataProvider->getModels() as $product): ?>
        <tr>
            <td><?= $product->marka->name ?></td>
            <td><?= $product->model->name ?></td>
            <td><?= $product->color->name ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
        </tr>
    <?php endforeach ?>
</table>