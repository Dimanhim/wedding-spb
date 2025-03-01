<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Manager */

$this->title = 'Добавление менеджера';
$this->params['breadcrumbs'][] = ['label' => 'Менеджеры', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="manager-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'manager' => $manager,
        'user' => $user,
    ]) ?>
</div>