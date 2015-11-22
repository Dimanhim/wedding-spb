<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Size */

$this->title = 'Редактирование размера: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Размеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="size-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>