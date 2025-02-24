<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Category;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StaticPagesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статичные страницы';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="static-page-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Добавить статичную страницу', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            // 'filter_name',
            'alias',
            // 'h1',
            // 'title',
            // 'keywords',
            // 'description',
            // 'content:ntext',
            [
                'attribute' => 'type',
                'value' => function($data) {
                    return $data->typeName;
                },
                'filter' => Html::activeDropDownList($searchModel, 'type', $searchModel->typeArr, ['class'=>'form-control', 'prompt' => 'Не важно']),
            ],
            [
                'attribute' => 'parent_id',
                'value' => function($data) {
                    return ($data->parent) ? $data->parent->name : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'parent_id', ArrayHelper::map(Category::find()->where(['in', 'id', [1, 2, 14, 21]])->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Не важно']),
            ],
            // [
            //     'attribute' => 'fashion_id',
            //     'value' => function($data) {
            //         return ($data->fashion) ? $data->fashion->name : '-';
            //     },
            // ],
            // [
            //     'attribute' => 'feature_id',
            //     'value' => function($data) {
            //         return ($data->feature) ? $data->feature->name : '-';
            //     },
            // ],
            // [
            //     'attribute' => 'color_id',
            //     'value' => function($data) {
            //         return ($data->color) ? $data->color->name : '-';
            //     },
            // ],
            // [
            //     'attribute' => 'price_cat_id',
            //     'value' => function($data) {
            //         return ($data->priceCategory) ? $data->priceCategory->name : '-';
            //     },
            // ],
            // 'min_price',
            // 'max_price',
            'is_deleted:boolean',
            // [
            //     'attribute' => 'created_at',
            //     'value'=> function($data) {
            //         return date('d.m.Y H:i', $data->created_at);
            //     }
            // ],
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>