<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Occasion */

$this->title = 'Редактирование повода: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Поводы', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>

<div class="occasion-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>