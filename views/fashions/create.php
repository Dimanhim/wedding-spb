<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fashion */

$this->title = 'Добавление фасона';
$this->params['breadcrumbs'][] = ['label' => 'Фасоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="silhouette-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>