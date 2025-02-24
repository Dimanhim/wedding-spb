<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\ArticleCategory;
use yii\helpers\ArrayHelper;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use dosamigos\tinymce\TinyMce;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]); ?>
    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(ArticleCategory::find()->orderBy('name ASC')->all(), 'id', 'name'), ['prompt' => 'Выберите категорию']) ?>
    <?= $form->field($model, 'image_field')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'browseLabel' => 'Выбрать',
            'showPreview' => false,
            'showUpload' => false,
            'showRemove' => false,
        ]
    ]); ?>
    <?php if ($model->image): ?>
        <div class="image-preview">
            <?= EasyThumbnailImage::thumbnailImg(Yii::$app->basePath.'/public_html'.$model->image, 100, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
            <p><?= Html::a('Удалить', ['delete-image', 'id' => $model->id], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>
    <?= $form->field($model, 'introtext')->widget(CKEditor::className(), [
        'options' => ['rows' => 6],
        'preset' => 'full',
    ]) ?>
    <?= $form->field($model, 'content')->widget(CKEditor::className(), [
        'options' => ['rows' => 15],
        'preset' => 'full',
    ]) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>