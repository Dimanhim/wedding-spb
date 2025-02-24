<?php

use yii\helpers\Html;

?>

<table class="table">
    <tr class="info">
        <th>марка</th>
        <th>модель</th>
        <th>цвет</th>
        <th class="amount_th">2к</th>
        <th class="amount_th">3к</th>
    </tr>

    <?php foreach ($dataProvider->getModels() as $product): ?>
        <tr>
            <td><?= $product->marka->name ?></td>
            <td><?= $product->model->name ?></td>
            <td><?= $product->color->name ?></td>
            <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
            <?= isset($product->amounts[3]) ? '<td class="amount amount_'.$product->amounts[3]->amount.'">'.$product->amounts[3]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        </tr>
    <?php endforeach ?>
</table>