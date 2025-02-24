<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Primerka */

$this->title = 'Добавление примерки';
$this->params['breadcrumbs'][] = ['label' => 'Примерки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="primerka-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>