<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrimerkiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Примерки';
$this->params['breadcrumbs'][] = $this->title;

$grouped_primerki = [];
foreach ($primerki as $primerka) {
    $grouped_primerki[date('d.m.Y', $primerka->date)][date('H', $primerka->date)][] = $primerka;
}
$max_totals = [];
?>

<div class="primerka-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="start-form">
        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => Url::to(['primerki/index']),
        ]); ?>
        <div class="form-group">
            <label class="control-label">Дата начала</label>
            <?= DatePicker::widget([
                'name' => 'start',
                'value' => isset($_REQUEST['start']) ? $_REQUEST['start'] : '',
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'options' => ['autocomplete' => 'off'],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy'
                ]
            ]) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <table class="table table-bordered primerki-table">
        <tr>
            <th></th>
            <?php foreach ($days as $day): ?>
                <?php
                    // $max_total = 0;
                    //$max_total = (Yii::$app->formatter->asDate($day, 'php:N') == 6 || Yii::$app->formatter->asDate($day, 'php:N') == 7) ? 1 : 0;
                    $max_total = 0;
                    $max_totals[Yii::$app->formatter->asDate($day, 'php:d.m.Y')] = $max_total + 1;

                    // $max_total = 0;
                    // foreach ($hours as $hour) {
                    //     $total_primerki = isset($grouped_primerki[Yii::$app->formatter->asDate($day, 'php:d.m.Y')][$hour]) ?
                    //         count($grouped_primerki[Yii::$app->formatter->asDate($day, 'php:d.m.Y')][$hour]) : 0;
                    //     if ($total_primerki > $max_total) $max_total = $total_primerki;
                    // }
                    // $max_totals[Yii::$app->formatter->asDate($day, 'php:d.m.Y')] = $max_total + 1;
                ?>
                <th colspan="<?= $max_total + 1 ?>"><?= Yii::$app->formatter->asDate($day, 'php:D d.m.Y'); ?></th>
            <?php endforeach ?>
        </tr>
        <?php foreach ($hours as $hour): ?>
            <tr>
                <td class="time"><?= $hour ?></td>
                <?php foreach ($days as $day): ?>
                    <?php
                        for ($i = 0; $i < $max_totals[Yii::$app->formatter->asDate($day, 'php:d.m.Y')]; $i++) {
                            $this_primerka = isset($grouped_primerki[Yii::$app->formatter->asDate($day, 'php:d.m.Y')][$hour][$i]) ?
                                $grouped_primerki[Yii::$app->formatter->asDate($day, 'php:d.m.Y')][$hour][$i] : null;
                            if ($this_primerka) {
                                echo '<td class="occupied result-'.$this_primerka->result.'"><a href="'.Url::to(['primerki/view', 'id' => $this_primerka->id]).'"></a></td>';
                            } else {
                                echo '<td class="free"><a href="'.Url::to(['primerki/create', 'date' => strtotime(Yii::$app->formatter->asDate($day, 'php:d.m.Y').' '.$hour.':00')]).'"></a></td>';
                            }
                        }
                    ?>
                <?php endforeach ?>
            </tr>
        <?php endforeach ?>
    </table>
</div>
