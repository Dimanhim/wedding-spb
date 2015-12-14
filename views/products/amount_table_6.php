<table class="table">
    <tr>
        <th></th>
        <th>Наличие</th>
    </tr>
    <tr>
        <?= isset($product->amounts[0]) ? '<td class="amount amount_'.$product->amounts[0]->amount.'">'.$product->amounts[0]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>зал</td>
    </tr>
    <tr>
        <?= isset($product->amounts[1]) ? '<td class="amount amount_'.$product->amounts[1]->amount.'">'.$product->amounts[1]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>склад</td>
    </tr>
    <tr>
        <?= isset($product->amounts[2]) ? '<td class="amount amount_'.$product->amounts[2]->amount.'">'.$product->amounts[2]->amount.'</td>' : '<td class="amount amount_0">0</td>' ?>
        <td>ждем</td>
    </tr>
</table>