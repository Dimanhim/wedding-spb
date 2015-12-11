<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Добавить операцию', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <table class="table">
        <tr class="active">
            <th></th>
            <th colspan="2">Доходы</th>
            <th colspan="2">Расходы</th>
            <th></th>
            <th colspan="2">Планируемый расход</th>
        </tr>
        <tr class="active">
            <th>Дата</th>
            <th>Нал</th>
            <th>Безнал</th>
            <th>Нал</th>
            <th>Безнал</th>
            <th>Итого</th>
            <th>Нал</th>
            <th>Безнал</th>
        </tr>
        <?php foreach ($days as $day): ?>
            <tr>
                <td><?= date('d.m.Y', $day) ?></td>
                <td><?= $operations[date('j', $day)]['day_income_nal'] ?></td>
                <td><?= $operations[date('j', $day)]['day_income_beznal'] ?></td>
                <td><?= $operations[date('j', $day)]['day_expense_nal'] ?></td>
                <td><?= $operations[date('j', $day)]['day_expense_beznal'] ?></td>
                <td><?= $operations[date('j', $day)]['day_summary'] ?></td>
                <td>-</td>
                <td>-</td>
            </tr>
        <?php endforeach ?>
        <tr class="active">
            <th>Итого</th>
            <th><?= $total['total_income_nal'] ?></th>
            <th><?= $total['total_income_beznal'] ?></th>
            <th><?= $total['total_expense_nal'] ?></th>
            <th><?= $total['total_expense_beznal'] ?></th>
            <th><?= $total['total_summary'] ?></th>
            <th>-</th>
            <th>-</th>
        </tr>
    </table>

</div>
