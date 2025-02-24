<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = 'Редактирование менеджера: ' . ' ' . $model->fio;
$this->params['breadcrumbs'][] = ['label' => 'Менеджеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->fio, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="user-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form_edit', [
        'manager' => $model,
    ]) ?>
</div>