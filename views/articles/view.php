<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use himiklab\thumbnail\EasyThumbnailImage;

/* @var $this yii\web\View */
/* @var $model app\models\Article */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Статьи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="article-view">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить статью?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?php if ($model->image): ?>
        <h3>Изображение</h3>
        <div class="old_img" style="margin-bottom: 20px;">
            <?= EasyThumbnailImage::thumbnailImg(Yii::$app->basePath.'/public_html'.$model->image, 100, 150, EasyThumbnailImage::THUMBNAIL_OUTBOUND) ?>
        </div>
    <?php endif ?>
    <?php if ($model->introtext): ?>
        <h3>Описание</h3>
        <div class="content" style="margin-bottom: 20px;">
            <?= $model->introtext ?>
        </div>
    <?php endif ?>
    <?php if ($model->content): ?>
        <h3>Контент</h3>
        <div class="content" style="margin-bottom: 20px;">
            <?= $model->content ?>
        </div>
    <?php endif ?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            [
                'attribute' => 'category_id',
                'value' => $model->category ? $model->category->name : '-',
            ],
            // 'introtext',
            // 'image',
            // 'content:ntext',
            [
                'attribute' => 'created_at',
                'value' => date('d.m.Y H:i', $model->created_at),
            ],
            [
                'attribute' => 'updated_at',
                'value' => date('d.m.Y H:i', $model->updated_at),
            ],
        ],
    ]) ?>
</div>