<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use app\models\PriceCategory;
use app\models\Fashion;
use app\models\Feature;
use app\models\Occasion;
use app\models\SiteColor;
use app\models\Category;
use kartik\file\FileInput;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\models\StaticPage */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="static-page-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'filter_name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'alias')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'h1')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'keywords')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
    ]) ?>
    <?= $form->field($model, 'type')->dropDownList($model->typeArr) ?>
    <?= $form->field($model, 'parent_id')->dropDownList(ArrayHelper::map(Category::find()->where(['in', 'id', [1, 2, 14, 21]])->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'fashion_id')->dropDownList(ArrayHelper::map(Fashion::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'feature_id')->dropDownList(ArrayHelper::map(Feature::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'occasion_id')->dropDownList(ArrayHelper::map(Occasion::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'color_id')->dropDownList(ArrayHelper::map(SiteColor::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'price_cat_id')->dropDownList(ArrayHelper::map(PriceCategory::find()->all(), 'id', 'name'), ['prompt' => '-']) ?>
    <?= $form->field($model, 'min_price')->textInput() ?>
    <?= $form->field($model, 'max_price')->textInput() ?>
    <?= $form->field($model, 'show_in_slider')->checkbox() ?>
    <?= $form->field($model, 'slider_image_field')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'browseLabel' => 'Выбрать',
            'showPreview' => false,
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]); ?>
    <?php if ($model->slider_image): ?>
        <div class="image-preview">
            <?= EasyThumbnailImage::thumbnailImg(Yii::$app->basePath.'/public_html'.$model->slider_image, 150, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
            <p><?= Html::a('Удалить', ['delete-slider-image', 'id' => $model->id], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>
    <?= $form->field($model, 'is_deleted')->checkbox() ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>