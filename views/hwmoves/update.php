<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\HWMove */

$this->title = 'Редактирование перемещения: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Перемещения из зала на склад', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="hwmove-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>