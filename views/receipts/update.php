<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */

$this->title = 'Оформление чека № ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Чеки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Оформление';
?>
<div class="receipt-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p><?= Html::a('Добавить товар', ['add-item', 'id' => $model->id], ['class' => 'btn btn-primary']) ?></p>
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
    		<th></th>
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
                                    'label' => false
                                ],
                            ]
                        ]);
                        ActiveForm::end();
                    ?>
                </td>
	    		<td><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-item', 'id' => $receipt_item->id]), [
                    'class' => 'btn btn-danger btn-xs',
                    'title' => 'Удалить',
                    'data' => [
                        'confirm' => 'Вы уверены, что хотите удалить товар?',
                        'method' => 'post',
                    ],
                ]) ?></td>
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
    <div class="receipt_actions">
        <?php
            $form = ActiveForm::begin(['type' => ActiveForm::TYPE_INLINE, 'action' => Url::toRoute(['add-sale', 'receipt_id' => $model->id])]);
            echo Form::widget([
                'formName' => 'sale',
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'amount' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'number'],
                        'columnOptions' => ['colspan' => 4],
                        'label' => 'Сделать скидку:'
                    ],
                    'product' => [
                        'type' => Form::INPUT_DROPDOWN_LIST, 
                        'items'=> ArrayHelper::map($model->items, 'id', function($model) {
                            return $model->product->marka->name;
                        }),
                        'columnOptions' => ['colspan' => 4],
                        'options' => ['prompt' => 'Выберите товар'],
                    ],
                    'actions' => [
                        'type' => Form::INPUT_RAW, 
                        'value'=> Html::submitButton('Сделать скидку', ['class'=>'btn btn-primary'])
                    ],
                ]
            ]);
            ActiveForm::end();
        ?>
    </div>
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
                        'options' => ['prompt' => 'Выберите оплату'],
                    ],
                    'change' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'number'],
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'actions' => [
                        'type' => Form::INPUT_RAW, 
                        'value'=> Html::submitButton('Оформить чек', ['class'=>'btn btn-success pull-right'])
                    ],
                ]
            ]);
            ActiveForm::end();
        ?>
    </div>
</div>