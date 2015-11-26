<?php

use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use app\models\Mark;
use app\models\Model;
use app\models\Color;
use app\models\Rate;
use app\models\Size;
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

        $sizes = [];
        foreach ($model->amounts as $amount) {
            $sizes[$amount->size_id] = $amount->size->name;
        }
        $model->sizes = array_keys($sizes);

        $form = ActiveForm::begin(['options' => ['enctype'=>'multipart/form-data']]);
        echo $form->errorSummary($model);
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
                    'items' => ArrayHelper::map(Size::find()->orderBy('name')->asArray()->all(), 'id', 'name'),
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
                                    <?php $size_amount1 = $model->getAmounts()->where(['size_id' => $key, 'amount_type' => 0])->one()->amount ?>
                                    <input type="number" class="form-control" value="<?= $size_amount1 ?>" name="Product[amount][<?= $key ?>][]">
                                </td>
                            <?php endforeach ?>
                            <td>Зал</td>
                        </tr>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <td>
                                    <?php $size_amount2 = $model->getAmounts()->where(['size_id' => $key, 'amount_type' => 1])->one()->amount ?>
                                    <input type="number" class="form-control" value="<?= $size_amount2 ?>" name="Product[amount][<?= $key ?>][]">
                                </td>
                            <?php endforeach ?>
                            <td>Склад</td>
                        </tr>
                        <tr>
                            <?php foreach ($sizes as $key => $size): ?>
                                <td>
                                    <?php $size_amount3 = $model->getAmounts()->where(['size_id' => $key, 'amount_type' => 2])->one()->amount ?>
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
                'description' => ['type'=>Form::INPUT_TEXTAREA],
                'photo' => ['type'=>Form::INPUT_FILE],
            ]
        ]);
    ?>

    <?php if ($model->photo): ?>
        <div class="old_img" style="margin-bottom: 20px;">
            <?= EasyThumbnailImage::thumbnailImg(\Yii::$app->basePath.$model->photo,100,150,EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
        </div>
    <?php endif ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>