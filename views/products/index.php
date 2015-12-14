<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;
use kartik\date\DatePicker;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="search_block">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#filter_block" aria-expanded="false" aria-controls="filter_block">Фильтры</a>
        <a class="btn btn-info cart_link" href="#" title="Корзина"><span class="glyphicon glyphicon-shopping-cart"></span></a>
        <?= Html::a('Добавить товар', ['create', 'category_id' => $category->id], ['class' => 'btn btn-success']) ?>
        <?= $this->render('_search', ['model' => $searchModel, 'category_id' => $category->id]); ?>
    </div>

    <form action="" id="products_form">
        <?= 
            $this->render('index_'.$category->type, [
                'sizes' => $sizes,
                'category' => $category,
                'dataProvider' => $dataProvider
            ]);
        ?>
    </form>

    <?= 
        LinkPager::widget([
            'pagination' => $pagination,
        ]);
    ?>
    
    <div class="panel panel-default" id="minicart">
        <div class="panel-heading">Заказ</div>
        <div class="panel-body">
            <p>Выбрано товаров: <span id="total_amount">0</span></p>
            <p>Общая сумма: <span id="total_price">0</span></p>
            <p>
                <?=
                    DatePicker::widget([
                        'language' => 'ru',
                        'type' => DatePicker::TYPE_INPUT,
                        'name' => 'await_date', 
                        //'value' => date('d-M-Y'),
                        'options' => ['placeholder' => 'Дата действия'],
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ]);
                ?>
            </p>
            <?= Html::a('Покупка', Url::toRoute(['orders/create']), ['class' => 'btn btn-success btn-block order_create', 'title' => 'Покупка']) ?>
            <?= Html::a('Со склада в зал', Url::toRoute(['whmoves/create']), ['class' => 'btn btn-primary btn-block whmove_create', 'title' => 'Со склада в зал']) ?>
            <?= Html::a('Из зала на склад', Url::toRoute(['hwmoves/create']), ['class' => 'btn btn-warning btn-block hwmove_create', 'title' => 'Из зала на склад']) ?>
        </div>
    </div>

</div>