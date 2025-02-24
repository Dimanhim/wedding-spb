<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\SiteColor */

$this->title = 'Добавление цвета для сайта';
$this->params['breadcrumbs'][] = ['label' => 'Цвета для сайта', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-color-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
