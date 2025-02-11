<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Operation;

/* @var $this yii\web\View */
/* @var $model app\models\Operation */

$this->title = 'Операции за '.$day;
$this->params['breadcrumbs'][] = ['label' => 'Операции', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$total_price = 0;
foreach ($dataProvider->getModels() as $operation) {
    if ($operation->type_id == Operation::TYPE_INCOME) {
        $total_price += $operation->total_price;
    } else {
        $total_price -= $operation->total_price;
    }
}
?>
<div class="operation-view">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> операций</div>',
        'rowOptions' => function ($model, $index, $widget, $grid){
            return ['style' => ($model->type_id == Operation::TYPE_INCOME) ? 'background-color: rgba(0,128,0,0.5);' : 'background-color: rgba(175, 29, 29, 0.5);'];
        },
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'user_id',
                'value'=> function($data) {
                    return ($data->manager) ? $data->manager->fio : '-';
                }
            ],
            [
                'attribute' => 'type_id',
                'value'=> function($data) {
                    return $data->getTypeLabel();
                }
            ],
            [
                'attribute' => 'cat_id',
                'value'=> function($data) {
                    return $data->getCatLabel();
                }
            ],
            [
                'attribute' => 'payment_type',
                'value'=> function($data) {
                    return $data->getPayLabel();
                }
            ],
            [
                'attribute' => 'total_price',
                'format'=> ['decimal', 0]
            ],
            'repeated:boolean',
            [
                'attribute' => 'created_at',
                'label' => 'Время',
                'value'=> function($data) {
                    return date('H:i', $data->created_at);
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}{delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class' => 'btn btn-primary btn-xs', 'title' => 'Посмотреть']);
                    },
                    'update' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class' => 'btn btn-info btn-xs', 'title' => 'Редактировать']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                            'class' => 'btn btn-danger btn-xs',
                            'title' => 'Удалить',
                            'data' => [
                                'confirm' => 'Вы уверены, что хотите удалить операцию?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>

    <p class="operations_summary">
        Итого: <strong><?= Yii::$app->formatter->asDecimal($total_price, 0) ?> р.</strong>
    </p>

</div>
