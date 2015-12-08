<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\models\Category;
use kartik\alert\AlertBlock;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Wedding CRM',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $cat_items = [];
    $categories = Category::find()->all();
    foreach ($categories as $category) {
        $cat_items[] = [
            'label' => $category->name,
            'url' => ['/products/index', 'category_id' => $category->id],
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Товары', 'url' => ['/products/index'], 'items' => $cat_items],
            ['label' => 'Заказы', 'url' => ['/orders/index']],
            ['label' => 'Перемещения', 'url' => ['/whmoves/index'], 'items' =>[
                ['label' => 'Со склада в зал', 'url' => ['/whmoves/index']],
                ['label' => 'Из зала на склад', 'url' => ['/hwmoves/index']],
            ]],
            ['label' => 'Продажи', 'url' => ['/receipts/index']],
            ['label' => 'Менеджеры', 'url' => ['/managers/index']],
            ['label' => 'Финансовый учет', 'url' => ['/site/contact']],
            ['label' => 'Справочники', 'url' => ['/site/contact'], 'items' =>[
                ['label' => 'Категории', 'url' => ['/categories/index']],
                ['label' => 'Марки', 'url' => ['/marks/index']],
                ['label' => 'Модели', 'url' => ['/models/index']],
                ['label' => 'Цвета', 'url' => ['/colors/index']],
                ['label' => 'Размеры', 'url' => ['/sizes/index']],
                ['label' => 'Коэффициенты', 'url' => ['/rates/index']],
            ]],
            [
                'label' => 'Выход (' . Yii::$app->user->identity->username . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= AlertBlock::widget([
            'useSessionFlash' => true,
            'type' => AlertBlock::TYPE_GROWL
        ]) ?>
        <?= Breadcrumbs::widget([
            'homeLink' => [ 
                'label' => 'Главная',
                'url' => Yii::$app->homeUrl,
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
