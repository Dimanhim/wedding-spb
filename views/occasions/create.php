<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Occasion */

$this->title = 'Добавление повода';
$this->params['breadcrumbs'][] = ['label' => 'Поводы', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="occasion-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>