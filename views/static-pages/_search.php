<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StaticPagesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-page-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'filter_name') ?>

    <?= $form->field($model, 'alias') ?>

    <?= $form->field($model, 'h1') ?>

    <?php // echo $form->field($model, 'title') ?>

    <?php // echo $form->field($model, 'keywords') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'parent_id') ?>

    <?php // echo $form->field($model, 'silhouette_id') ?>

    <?php // echo $form->field($model, 'color_id') ?>

    <?php // echo $form->field($model, 'price_cat_id') ?>

    <?php // echo $form->field($model, 'min_price') ?>

    <?php // echo $form->field($model, 'max_price') ?>

    <?php // echo $form->field($model, 'is_deleted') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
