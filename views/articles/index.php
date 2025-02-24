<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\ArticleCategory;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Статьи';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?= Html::a('Добавить статью', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'category_id',
                'value'=> function($data) {
                    return $data->category ? $data->category->name : '-';
                },
                'filter' => Html::activeDropDownList($searchModel, 'category_id', ArrayHelper::map(ArticleCategory::find()->orderBy('name ASC')->all(), 'id', 'name'), ['class'=>'form-control', 'prompt' => 'Выберите категорию']),
            ],
            // 'introtext',
            // 'image',
            // 'content:ntext',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y', $data->created_at);
                }
            ],
            // 'updated_at',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>