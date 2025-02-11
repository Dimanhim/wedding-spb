<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\form\ActiveForm;
use kartik\builder\Form;
use yii\helpers\ArrayHelper;
use app\models\Manager;
use kartik\datecontrol\DateControl;

/* @var $this yii\web\View */
/* @var $model app\models\Receipt */

$this->title = 'Оформление чека № ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Чеки', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Оформление';
?>
<div class="receipt-create">
    <h1><?= Html::encode($this->title) ?></h1>
    <p class="form-inline">
        <?= Html::textInput('barcode', null, ['placeholder' => 'Штрих-код', 'class' => 'form-control']) ?>
        <?= Html::a('Добавить товар', ['add-item', 'barcode' => ''], ['class' => 'btn btn-primary add_item_by_barcode']) ?>

        <?= Html::activeDropDownList($model, 'manager_id', ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', 'name'),
            ['prompt' => 'Выберите менеджера', 'class' => 'form-control']) ?>
        <?= Html::a('Сменить менеджера', ['change-manager', 'id' => $model->id], ['class' => 'btn btn-primary change_manager']) ?>

        <?= DateControl::widget([
            'model' => $model,
            'name' => 'created_at', 
            'value' => $model->created_at,
            'type' => DateControl::FORMAT_DATE,
            'saveOptions' => ['data-id' => $model->id],
            'options' => [
                'removeButton' => false,
            ]
        ]); ?>
    </p>
    <p>Организация: <strong>ИР Аронов ГЗ</strong> ИНН: <strong>781300200468</strong>  Магазин: <strong>Праздник</strong></p>
    <p>
        Товарный чек от <strong><?= date('d.m.Y', $model->created_at) ?></strong>.
        Продавец - <strong><?= $model->manager ? $model->manager->fio : Yii::$app->user->identity->username ?></strong>
    </p>
    
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
	    		<td><?= ($receipt_item->product->marka) ? $receipt_item->product->marka->name : '-' ?></td>
	    		<td><?= ($receipt_item->product->model) ? $receipt_item->product->model->name : '-' ?></td>
	    		<td><?= ($receipt_item->product->color) ? $receipt_item->product->color->name : '-' ?></td>
	    		<td><?= ($receipt_item->size) ? $receipt_item->size->name : '-' ?></td>
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
	    		<td><?= Html::a('<span class="glyphicon glyphicon-trash"></span>', Url::toRoute(['delete-item', 'receipt_id' => $model->id, 'item_id' => $receipt_item->id]), [
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
    <div class="receipt_actions row">
        <div class="col-md-12">
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
    </div>
    <hr>
    <div class="receipt_payment">
        <?php
            $form = ActiveForm::begin(['type' => ActiveForm::TYPE_VERTICAL, 'action' => Url::toRoute(['save', 'id' => $model->id])]);
            echo Form::widget([
                'model' => $model,
                'form' => $form,
                'columns' => 12,
                'attributes' => [
                    'payment_type' => [
                        'type' => Form::INPUT_DROPDOWN_LIST, 
                        'items' => $model->getPayCashes(),
                        'columnOptions' => ['colspan' => 3],
                        'labelOptions' => ['class'=>'col-md-4'],
                        'inputContainer' => ['class'=>'col-md-8'],
                    ],
                    'change' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'number'],
                        'columnOptions' => ['colspan' => 4],
                    ],
                    'card_number' => [
                        'type' => Form::INPUT_TEXT,
                        'columnOptions' => ['colspan' => 4],
                        'label' => 'Карта клиента:'
                    ],
                    'actions' => [
                        'type' => Form::INPUT_RAW, 
                        'value'=> Html::submitButton('Оформить чек', ['class'=>'btn btn-success pull-right']),
                    ],
                    'is_closed' => [
                        'type' => Form::INPUT_HIDDEN,
                        'label' => false,
                    ],
                    'cash_total' => [
                        'type' => Form::INPUT_TEXT,
                        'options' => ['type' => 'number'],
                        'columnOptions' => ['colspan' => 3, 'class' => 'cash_total_block'],
                    ],
                ]
            ]);
            ActiveForm::end();
        ?>
    </div>
</div>

<?php
    $this->registerJs('
       $(function() {
           var table = $(".receipt-create > .table");
           setInterval(function() {
               $.ajax({
                   url: document.location.href,
               }).done(function(data) {
                   table.html($(data).find(".table").html());
               });
           }, 1000)
       });
    ', 3, 'reciept-refresh');
?>