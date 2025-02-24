<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fashion */

$this->title = 'Редактирование фасона: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Фасоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="fashion-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>