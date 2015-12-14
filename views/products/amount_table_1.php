<table class="table">
    <tr>
        <?php foreach ($sizes as $size): ?>
            <th class="amount_th"><?= $size->name ?></th>
        <?php endforeach ?>
        <th>Наличие</th>
    </tr>
    <tr>
        <?php foreach ($sizes as $size): ?>
            <?php
                $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 0])->one();
                echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
            ?>
        <?php endforeach ?>
        <td>зал</td>
    </tr>
    <tr>
        <?php foreach ($sizes as $size): ?>
            <?php
                $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 1])->one();
                echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
            ?>
        <?php endforeach ?>
        <td>склад</td>
    </tr>
    <tr>
        <?php foreach ($sizes as $size): ?>
            <?php
                $amount_size = $product->getAmounts()->where(['size_id' => $size->id, 'amount_type' => 2])->one();
                echo $amount_size ? '<td class="amount amount_'.$amount_size->amount.'">'.$amount_size->amount.'</td>' : '<td class="amount amount_0">0</td>';
            ?>
        <?php endforeach ?>
        <td>ждем</td>
    </tr>
</table>