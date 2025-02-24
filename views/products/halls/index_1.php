<?php
    use yii\helpers\Html;
?>

<?php $total_price = 0; ?>
<table class="table">
    <tr class="info">
        <th>марка</th>
        <th>модель</th>
        <th>цвет</th>
        <?php foreach ($sizes as $size): ?>
            <th class="amount_th"><?= $size->name ?></th>
        <?php endforeach ?>
        <th>цена</th>
    </tr>
    <?php foreach ($dataProvider->getModels() as $product): ?>
        <?php $sub_total_price = 0; ?>
        <tr>
            <td><?= $product->marka->name ?></td>
            <td><?= $product->model->name ?></td>
            <td><?= $product->color->name ?></td>
            <?php foreach ($sizes as $size): ?>
                <?php
                    $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                    if ($amount_size) {
                        $price = ($size->name < 50) ? $product->purchase_price_small : $product->purchase_price_big;
                        $sub_total_price += $price * $amount_size->amount;
                        $total_price += $price * $amount_size->amount;
                    }
                    echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
                ?>
            <?php endforeach ?>
            <td><?= Yii::$app->formatter->asDecimal($sub_total_price) ?></td>
        </tr>
    <?php endforeach ?>
</table>
<p><strong>Итого: </strong> <?= Yii::$app->formatter->asDecimal($total_price) ?></p>