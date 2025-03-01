<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Редактирование товара: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['index', 'category_id' => $category->id]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Редактирование';
?>
<div class="product-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('forms/_form_'.$category->type, [
        'model' => $model,
        'avail_sizes' => $sizes,
    ]) ?>
</div>