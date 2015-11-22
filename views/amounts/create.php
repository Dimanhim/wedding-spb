<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Amount */

$this->title = 'Create Amount';
$this->params['breadcrumbs'][] = ['label' => 'Amounts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="amount-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
