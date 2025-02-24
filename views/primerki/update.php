<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Primerka */

$this->title = 'Редактирование примерки: '.date('d.m.Y H:i', $model->date);
$this->params['breadcrumbs'][] = ['label' => 'Примерки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => date('d.m.Y H:i', $model->date), 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="primerka-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>