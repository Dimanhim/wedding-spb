<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Model */

$this->title = 'Редактирование модели: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Модели', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="model-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>