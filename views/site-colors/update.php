<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\SiteColor */

$this->title = 'Редактирование цвета для сайта: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Цвета для сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="site-color-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
