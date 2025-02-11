<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\Select2;
use app\models\Operation;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Операции';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="operation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="search_block">
        <div>
            <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
            <?= Html::a('Добавить операцию', ['create'], ['class' => 'btn btn-success']) ?>

            <?= Html::a('Сбросить', ['index'], ['class' => 'btn btn-warning pull-right']) ?>
            <?= Html::a('Применить', ['index'], ['class' => 'btn btn-primary pull-right operation_cat_filter_apply']) ?>
            <?=
                Select2::widget([
                    'name' => 'OperationSearch[cat_id]',
                    'data' => Operation::getCategories(),
                    'value' => isset($_GET['OperationSearch']['cat_id']) ? explode(',', $_GET['OperationSearch']['cat_id']) : [],
                    'showToggleAll' => false,
                    'options' => [
                        'placeholder' => 'Выберите категории ...',
                        'multiple' => true
                    ],
                ]);
            ?>
        </div>
        <?= $this->render('_search', ['model' => $searchModel]); ?>
    </div>

    <table class="table">
        <tr class="active">
            <th></th>
            <th colspan="2">Доходы</th>
            <th>Закупка</th>
            <th colspan="2">Расходы</th>
            <th colspan="2">Итого</th>
            <th colspan="2">Планируемый расход</th>
            <th></th>
        </tr>
        <tr class="active">
            <th>Дата</th>
            <th>Нал</th>
            <th>Безнал</th>
            <th></th>
            <th>Нал</th>
            <th>Безнал</th>
            <th>Нал</th>
            <th>Безнал</th>
            <th>Нал</th>
            <th>Безнал</th>
            <th></th>
        </tr>
        <?php foreach ($days as $day): ?>
            <tr>
                <td><?= date('d.m.Y', $day) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_income_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_income_beznal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_purchase_price'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_expense_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_expense_beznal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_summary_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_summary_beznal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_planned_nal'], 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($operations[date('Y', $day)][date('n', $day)][date('j', $day)]['day_planned_beznal'], 0) ?></td>
                <td>
                    <?= 
                        Html::a('<span class="glyphicon glyphicon-eye-open"></span>', 
                            Url::toRoute(['operations/view', 'day' => date('d.m.Y', $day)]), 
                            ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']
                        );
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr class="active">
            <th>Итого</th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_income_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_income_beznal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_purchase_price'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_expense_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_expense_beznal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_summary_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_summary_beznal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_planned_nal'], 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($total['total_planned_beznal'], 0) ?></th>
            <th></th>
        </tr>
    </table>

</div>
