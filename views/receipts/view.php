<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */

$this->title = 'Чек №'.$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Чеки', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="receipt-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Организация: <strong>ИР Аронов ГЗ</strong> ИНН: <strong>781300200468</strong>  Магазин: <strong>Праздник</strong></p>
    <p>Товарный чек от <strong><?= date('d.m.Y', $model->created_at) ?></strong>. Продавец - <strong><?= Yii::$app->user->identity->username ?></strong></p>
    
    <table class="table">
        <tr>
            <th>№</th>
            <th>Марка</th>
            <th>Модель</th>
            <th>Цвет</th>
            <th>Размер</th>
            <th>Кол-во</th>
            <th>Цена</th>
            <th>Скидка</th>
            <th>Итого</th>
            <th>П</th>
        </tr>
        <?php foreach ($model->items as $receipt_item): ?>
            <tr>
                <td><?= $receipt_item->id ?></td>
                <td><?= $receipt_item->product->marka->name ?></td>
                <td><?= $receipt_item->product->model->name ?></td>
                <td><?= $receipt_item->product->color->name ?></td>
                <td><?= $receipt_item->size->name ?></td>
                <td><?= $receipt_item->amount ?></td>
                <td><?= Yii::$app->formatter->asDecimal($receipt_item->price, 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($receipt_item->sale, 0) ?></td>
                <td><?= Yii::$app->formatter->asDecimal($receipt_item->total_price, 0) ?></td>
                <td>
                    <?php
                        $form = ActiveForm::begin(['action' => Url::toRoute(['make-gift', 'receipt_id' => $model->id, 'item_id' => $receipt_item->id])]);
                        echo Form::widget([
                            'model' => $receipt_item,
                            'form' => $form,
                            'attributes' => [
                                'gift' => [
                                    'type' => Form::INPUT_CHECKBOX,
                                    'label' => false,
                                    'options' => ['disabled' => 'disabled'],
                                ],
                            ]
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
            </tr>
        <?php endforeach ?>
        <tr>
            <th colspan="5">Итого</th>
            <th><?= $model->total_amount ?></th>
            <th><?= Yii::$app->formatter->asDecimal($model->price, 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($model->sale, 0) ?></th>
            <th><?= Yii::$app->formatter->asDecimal($model->total_price, 0) ?></th>
            <th colspan="2"></th>
        </tr>
    </table>
    <hr>
    <div class="receipt_payment">
        <?php
            $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'action' => Url::toRoute(['save', 'id' => $model->id])]);
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'payment_type' => [
                        'type' => Form::INPUT_DROPDOWN_LIST, 
                        'items' => $model->getPayCashes(),
                        'columnOptions' => ['colspan' => 4],
                        'options' => ['prompt' => 'Выберите оплату', 'disabled' => 'disabled'],
                    ],
                    'change' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'number'],
                        'columnOptions' => ['colspan' => 4],
                        'options' => ['disabled' => 'disabled'],
                    ],
                ]
            ]);
            ActiveForm::end();
        ?>
    </div>
</div>