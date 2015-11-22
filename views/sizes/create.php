<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Size */

$this->title = 'Добавление размера';
$this->params['breadcrumbs'][] = ['label' => 'Размеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="size-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>