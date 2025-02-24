<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PriceCategory */

$this->title = 'Добавить ценовую категорию';
$this->params['breadcrumbs'][] = ['label' => 'Ценовые категории', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="price-category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
