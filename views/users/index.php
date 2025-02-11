<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'summary' => '<div class="summary">Показаны <b>{begin}-{end}</b> из <b>{totalCount}</b> пользователей</div>',
        'columns' => [
            'id',
            'username',
            'email:email',
            // 'name',
            // 'surname',
            // 'fathername',
            // 'employment_date',
            // 'auth_key',
            // 'password_hash',
            // 'password_reset_token',
            // 'email_confirm_token:email',
            // 'role',
            // 'status',
            [
                'attribute' => 'created_at',
                'value'=> function($data) {
                    return date('d.m.Y H:i', $data->created_at);
                }
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update}',
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
                                'confirm' => 'Вы уверены, что хотите удалить пользователя?',
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>