<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use app\models\Mark;
use app\models\Model;
use app\models\Color;
use app\models\Rate;
use app\models\Category;
use app\models\PriceCategory;
use app\models\Fashion;
use app\models\Feature;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\models\Product */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="product-form">
    <?php
        $model->marka_or = 'Или';
        $model->model_or = 'Или';
        $model->color_or = 'Или';
        $model->ratio_or = 'Или';

        // Ценовые категории
        $price_categories = [];
        foreach ($model->productPriceCategories as $productPriceCategories) {
            $price_categories[] = $productPriceCategories->price_category_id;
        }
        $model->price_category = $price_categories;

        // Фасоны
        $fashions = [];
        foreach ($model->productFashions as $productFashion) {
            $fashions[] = $productFashion->fashion_id;
        }
        $model->fashion = $fashions;

        // Особенности
        $features = [];
        foreach ($model->productFeatures as $productFeature) {
            $features[] = $productFeature->feature_id;
        }
        $model->feature = $features;

        $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]);
        echo $form->errorSummary($model);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 2,
            'attributes' => [
                'hide_on_web' => ['type'=>Form::INPUT_CHECKBOX],
                'hide_on_tablet' => ['type'=>Form::INPUT_CHECKBOX],
                'new' => ['type'=>Form::INPUT_CHECKBOX],
            ]
        ]);
        if ($model->category) {
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 2,
                'attributes' => [
                    'category_id' => [
                        'type' => Form::INPUT_DROPDOWN_LIST,
                        'items' => ArrayHelper::map(Category::find()->where(['type' => $model->category->type])->orderBy('name')->asArray()->all(), 'id', 'name'),
                    ],
                    'position' => ['type'=>Form::INPUT_TEXT],
                ]
            ]);
        }
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'marka_id' => [
                    'type' => Form::INPUT_DROPDOWN_LIST, 
                    'items'=> ArrayHelper::map(Mark::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите марку'],
                ],
                'marka_or' => ['type'=>Form::INPUT_STATIC, 'label' => false, 'columnOptions' => ['colspan' => 2]],
                'marka_new' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 5], 'options' => ['disabled' => $model->marka_id]],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'model_id' => [
                    'type' => Form::INPUT_DROPDOWN_LIST, 
                    'items'=> ArrayHelper::map(Model::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите модель'],
                ],
                'model_or' => ['type'=>Form::INPUT_STATIC, 'label' => false, 'columnOptions' => ['colspan' => 2]],
                'model_new' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 5], 'options' => ['disabled' => $model->model_id]],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'color_id' => [
                    'type' => Form::INPUT_DROPDOWN_LIST, 
                    'items'=> ArrayHelper::map(Color::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите цвет'],
                ],
                'color_or' => ['type'=>Form::INPUT_STATIC, 'label' => false, 'columnOptions' => ['colspan' => 2]],
                'color_new' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 5], 'options' => ['disabled' => $model->color_id]],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'ratio_id' => [
                    'type' => Form::INPUT_DROPDOWN_LIST, 
                    'items'=> ArrayHelper::map(Rate::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите коэффициент'],
                ],
                'ratio_or' => ['type'=>Form::INPUT_STATIC, 'label' => false, 'columnOptions' => ['colspan' => 2]],
                'ratio_new' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 5], 'options' => ['disabled' => $model->ratio_id]],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 12,
            'attributes' => [
                'purchase_price' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 3]],
                'purchase_price_dol' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 3]],
                'price' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 3]],
                'old_price' => ['type'=>Form::INPUT_TEXT, 'columnOptions' => ['colspan' => 3]],
            ]
        ]);
    ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group field-amount-description">
                <label class="control-label" for="amount-description">Фактическое наличие товара</label>
                <table class="table table-bordered" id="amount_table">
                    <tr>
                        <th>Кол-во</th>
                        <th>Наличие</th>
                    </tr>
                    <tr>
                        <td><input type="number" class="form-control" value="<?= isset($model->amounts[0]) ? $model->amounts[0]->amount : 0 ?>" name="Product[amount][]"></td>
                        <td>Зал</td>
                    </tr>
                    <tr>
                        <td><input type="number" class="form-control" value="<?= isset($model->amounts[1]) ? $model->amounts[1]->amount : 0 ?>" name="Product[amount][]"></td>
                        <td>Склад</td>
                    </tr>
                    <tr>
                        <td><input type="number" class="form-control" value="<?= isset($model->amounts[2]) ? $model->amounts[2]->amount : 0 ?>" name="Product[amount][]"></td>
                        <td>Ждём</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <?php
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'price_category' => [
                    'type' => Form::INPUT_MULTISELECT, 
                    'items' => ArrayHelper::map(PriceCategory::find()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите ценовые категории'],
                ],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'fashion' => [
                    'type' => Form::INPUT_MULTISELECT, 
                    'items' => ArrayHelper::map(Fashion::find()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите фасоны'],
                ],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'feature' => [
                    'type' => Form::INPUT_MULTISELECT, 
                    'items' => ArrayHelper::map(Feature::find()->all(), 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите особенности'],
                ],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'description' => ['type'=>Form::INPUT_TEXTAREA],
                'instagram_description' => ['type'=>Form::INPUT_TEXT],
                'photo' => ['type'=>Form::INPUT_FILE],
            ]
        ]);
    ?>

    <?php if ($model->photo): ?>
        <div class="old_img" style="margin-bottom: 20px;">
            <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$model->photo, 100, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
            <p><?= Html::a('Удалить', ['delete-image', 'id' => $model->id, 'name' => 'photo'], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>

    <?= 
        Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'photo2' => ['type'=>Form::INPUT_FILE],
            ]
        ]);
    ?>

    <?php if ($model->photo2): ?>
        <div class="old_img" style="margin-bottom: 20px;">
            <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$model->photo2, 100, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
            <p><?= Html::a('Удалить', ['delete-image', 'id' => $model->id, 'name' => 'photo2'], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>

    <?= 
        Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 1,
            'attributes' => [
                'photo3' => ['type'=>Form::INPUT_FILE],
            ]
        ]);
    ?>
    
    <?php if ($model->photo3): ?>
        <div class="old_img" style="margin-bottom: 20px;">
            <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.'/public_html'.$model->photo3, 100, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
            <p><?= Html::a('Удалить', ['delete-image', 'id' => $model->id, 'name' => 'photo3'], ['class' => 'btn btn-xs btn-danger']) ?></p>
        </div>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>