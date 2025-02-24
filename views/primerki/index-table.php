<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Manager;
use app\models\Client;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PrimerkiSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Примерки';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="primerka-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Добавить примерку', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'manager_id',
                'value' => function($data) {
                    return ($data->manager) ? $data->manager->fio : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'manager_id', ArrayHelper::map(Manager::find()->orderBy('name ASC')->all(), 'id', function($model) {
                    return $model->fio;
                }), ['prompt' => 'Не важно', 'class' => 'form-control']),
            ],
            [
                'attribute' => 'client_id',
                'value' => function($data) {
                    return ($data->client) ? $data->client->fio : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'client_id', ArrayHelper::map(Client::find()->orderBy('fio ASC')->all(), 'id', 'fio'), ['prompt' => 'Не важно', 'class' => 'form-control']),
            ],
            [
                'attribute' => 'date',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->date);
                }
            ],
            // 'description:ntext',
            // 'result',
            // 'receipt_id',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->created_at);
                }
            ],
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>