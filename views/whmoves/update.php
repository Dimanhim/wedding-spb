<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WHMove */

$this->title = 'Редактирование перемещения: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещения со склада в зал', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="whmove-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>