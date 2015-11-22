<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

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
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Товары', 'url' => ['/products/index'], 'items' => [
                ['label' => 'Свадебные платья', 'url' => ['/site/about2']],
                ['label' => 'Вечерние платья', 'url' => ['/site/about2']],
                ['label' => 'Фата', 'url' => ['/site/about2']],
                ['label' => 'Болеро', 'url' => ['/site/about2']],
                ['label' => 'Шубки-накидки', 'url' => ['/site/about2']],
                ['label' => 'Обувь', 'url' => ['/site/about2']],
                ['label' => 'Пояса', 'url' => ['/site/about2']],
                ['label' => 'Украшение', 'url' => ['/site/about2']],
                ['label' => 'Подвязка', 'url' => ['/site/about2']],
                ['label' => 'Заколки', 'url' => ['/site/about2']],
                ['label' => 'Перчатки', 'url' => ['/site/about2']],
                ['label' => 'Чулки-колготки', 'url' => ['/site/about2']],
                ['label' => 'Бокалы', 'url' => ['/site/about2']],
                ['label' => 'Аксессуары для свадеб', 'url' => ['/site/about2']],
                ['label' => 'Кринолины', 'url' => ['/site/about2']],
                ['label' => 'Чехол- сумка с логотипом', 'url' => ['/site/about2']],
            ]],
            ['label' => 'Заказы', 'url' => ['/site/about']],
            ['label' => 'Перемещения', 'url' => ['/site/contact'], 'items' =>[
                ['label' => 'Со склада в зал', 'url' => ['/site/about2']],
                ['label' => 'Из зала на склад', 'url' => ['/site/about2']],
            ]],
            ['label' => 'Продажи', 'url' => ['/site/contact']],
            ['label' => 'Менеджеры', 'url' => ['/site/contact']],
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
        <?= Breadcrumbs::widget([
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
