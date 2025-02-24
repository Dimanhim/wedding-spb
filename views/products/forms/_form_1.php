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
use app\models\Size;
use app\models\Amount;
use app\models\PriceCategory;
use app\models\Fashion;
use app\models\Feature;
use app\models\Occasion;
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

        // Размеры
        $sizes = [];
        foreach ($model->amounts as $amount) {
            $sizes[$amount->size_id] = $amount->size->name;
        }
        ksort($sizes);
        $model->sizes = array_keys($sizes);

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

        // Поводы
        $occasions = [];
        foreach ($model->productOccasions as $productOccasion) {
            $occasions[] = $productOccasion->occasion_id;
        }
        $model->occasion = $occasions;

        $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]);
        echo $form->errorSummary($model);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 6,
            'attributes' => [
                'hide_on_web' => ['type'=>Form::INPUT_CHECKBOX],
                'hide_on_tablet' => ['type'=>Form::INPUT_CHECKBOX],
                'new' => ['type'=>Form::INPUT_CHECKBOX],
                'sale' => ['type'=>Form::INPUT_CHECKBOX],
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
            'columns' => 1,
            'attributes' => [
                'sizes' => [
                    'type' => Form::INPUT_MULTISELECT, 
                    'items' => ArrayHelper::map($avail_sizes, 'id', 'name'),
                    'columnOptions' => ['colspan' => 5],
                    'options' => ['prompt' => 'Выберите размер(-ы)'],
                ],
            ]
        ]);
        echo Form::widget([
            'model' => $model,
            'form' => $form,
            'columns' => 2,
            'attributes' => [
                'purchase_price_small' => ['type'=>Form::INPUT_TEXT],
                'purchase_price_big' => ['type'=>Form::INPUT_TEXT],
                'purchase_price_small_dol' => ['type'=>Form::INPUT_TEXT],
                'purchase_price_big_dol' => ['type'=>Form::INPUT_TEXT],
                'price_small' => ['type'=>Form::INPUT_TEXT],
                'price_big' => ['type'=>Form::INPUT_TEXT],
                'old_price_small' => ['type'=>Form::INPUT_TEXT],
                'old_price_big' => ['type'=>Form::INPUT_TEXT],
            ]
        ]);
    ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="form-group field-amount-description">
                <label class="control-label" for="amount-description">Фактическое наличие товара</label>
                <table class="table table-bordered" id="amount_table">
                    <?php if (count($sizes)): ?>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <th><?= $size ?></th>
                            <?php endforeach ?>
                            <th>Наличие</th>
                        </tr>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <td>
                                    <?php
                                        $size_amount_query1 = Amount::find()->where(['product_id' => $model->id, 'size_id' => $key, 'amount_type' => Amount::TYPE_HALL])->one();
                                        $size_amount1 = ($size_amount_query1) ? $size_amount_query1->amount : 0;
                                    ?>
                                    <input type="number" class="form-control" value="<?= $size_amount1 ?>" name="Product[amount][<?= $key ?>][]">
                                </td>
                            <?php endforeach ?>
                            <td>Зал</td>
                        </tr>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <td>
                                    <?php
                                        $size_amount_query2 = Amount::find()->where(['product_id' => $model->id, 'size_id' => $key, 'amount_type' => Amount::TYPE_WAREHOUSE])->one();
                                        $size_amount2 = ($size_amount_query2) ? $size_amount_query2->amount : 0;
                                    ?>
                                    <input type="number" class="form-control" value="<?= $size_amount2 ?>" name="Product[amount][<?= $key ?>][]">
                                </td>
                            <?php endforeach ?>
                            <td>Склад</td>
                        </tr>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <td>
                                    <?php
                                        $size_amount_query3 = Amount::find()->where(['product_id' => $model->id, 'size_id' => $key, 'amount_type' => Amount::TYPE_WAIT])->one();
                                        $size_amount3 = ($size_amount_query3) ? $size_amount_query3->amount : 0;
                                    ?>
                                    <input type="number" class="form-control" value="<?= $size_amount3 ?>" name="Product[amount][<?= $key ?>][]">
                                </td>
                            <?php endforeach ?>
                            <td>Ждём</td>
                        </tr>
                    <?php else: ?>
                        <tr>
                            <th>Наличие</th>
                        </tr>
                        <tr>
                            <td>Зал</td>
                        </tr>
                        <tr>
                            <td>Склад</td>
                        </tr>
                        <tr>
                            <td>Ждём</td>
                        </tr>
                    <?php endif ?>
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
                'occasion' => [
                    'type' => Form::INPUT_MULTISELECT, 
                    'items' => ArrayHelper::map(Occasion::find()->all(), 'id', 'name'),
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