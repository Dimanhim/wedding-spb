<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Manager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ClientsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Клиенты';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="client-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Добавить клиента', ['create'], ['class' => 'btn btn-success']) ?>
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
            'fio',
            'phone',
            'email:email',
            // 'first_visit',
            // 'visit_purpose',
            // 'is_appoint',
            // 'birtday',
            // 'event_date',
            // 'wedding_place',
            // 'source',
            // 'description:ntext',
            'ip',
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