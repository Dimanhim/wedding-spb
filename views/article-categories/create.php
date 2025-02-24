<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ArticleCategory */

$this->title = 'Добавление категории статей';
$this->params['breadcrumbs'][] = ['label' => 'Категории статей', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-category-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>