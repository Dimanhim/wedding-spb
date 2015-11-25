<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Product */

$this->title = 'Добавление товара';
$this->params['breadcrumbs'][] = ['label' => $category->name, 'url' => ['index', 'category_id' => $category->id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form_'.$category->type, [
        'model' => $model,
    ]) ?>
</div>