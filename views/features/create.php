<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Feature */

$this->title = 'Добавление особенности';
$this->params['breadcrumbs'][] = ['label' => 'Особенности', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="feature-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>