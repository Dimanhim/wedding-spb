<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Mark */

$this->title = 'Добавление марки';
$this->params['breadcrumbs'][] = ['label' => 'Марки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mark-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>