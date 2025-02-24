<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<table class="table">
    <tr>
        <th class="amount_th">2к</th>
        <th class="amount_th">3к</th>
        <th>Наличие</th>
    </tr>
    <tr>
        <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <?= isset($product->amounts[3]) ? '<td class="amount amount_'.$product->amounts[3]->amount.'">'.$product->amounts[3]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>зал</td>
    </tr>
    <tr>
        <?= isset($product->amounts[1]) ? '<td class="amount amount_'.$product->amounts[1]->amount.'">'.$product->amounts[1]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <?= isset($product->amounts[4]) ? '<td class="amount amount_'.$product->amounts[4]->amount.'">'.$product->amounts[4]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>склад</td>
    </tr>
    <tr>
        <?= isset($product->amounts[2]) ? '<td class="amount amount_'.$product->amounts[2]->amount.'">'.$product->amounts[2]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <?= isset($product->amounts[5]) ? '<td class="amount amount_'.$product->amounts[5]->amount.'">'.$product->amounts[5]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>ждем</td>
    </tr>
    <tr>
        <td>
            <?= Html::a('Штрих-код', ['products/barcode', 'id' => $product->getBarcode($product, 1)], ['class' => 'btn btn-block btn-primary', 'target' => '_blank']) ?>
        </td>
        <td>
            <?= Html::a('Штрих-код', ['products/barcode', 'id' => $product->getBarcode($product, 2)], ['class' => 'btn btn-block btn-primary', 'target' => '_blank']) ?>
        </td>
        <td>штрих-коды</td>
    </tr>
</table>