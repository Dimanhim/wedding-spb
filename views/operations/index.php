<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="search_block">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
        <?= Html::a('Добавить операцию', ['create'], ['class' => 'btn btn-success']) ?>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

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
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_income_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_income_beznal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_expense_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_expense_beznal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_summary'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_planned_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('j', $day)]['day_planned_beznal'], 0) ?></td>
            </tr>
        <?php endforeach ?>
        <tr class="active">
            <th>Итого</th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_income_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_income_beznal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_expense_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_expense_beznal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_summary'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_planned_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_planned_beznal'], 0) ?></th>
        </tr>
    </table>

</div>
