<?php

use yii\helpers\Html;
use himiklab\thumbnail\EasyThumbnailImage;
use yii\helpers\Url;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ProductSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $category->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="product-search">
        <a class="btn btn-primary" role="button" data-toggle="collapse" href="#products_filter" aria-expanded="false" aria-controls="products_filter">Фильтры</a>
        <a class="btn btn-info cart_link" href="#" title="Корзина"><span class="glyphicon glyphicon-shopping-cart"></span></a>
        <?= Html::a('Добавить товар', ['create', 'category_id' => $category->id], ['class' => 'btn btn-success']) ?>
        <?= $this->render('_search', ['model' => $searchModel, 'category_id' => $category->id]); ?>
    </div>
    
    <?= 
        $this->render('index_'.$category->type, [
            'sizes' => $sizes,
            'category' => $category,
            'dataProvider' => $dataProvider
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
                        'name' => 'check_issue_date', 
                        //'value' => date('d-M-Y'),
                        'options' => ['placeholder' => 'Введите примерную дату ожидания'],
                        'pluginOptions' => [
                            'format' => 'dd.mm.yyyy',
                            'todayHighlight' => true
                        ]
                    ]);
                ?>
            </p>
            <?= Html::a('Покупка', Url::toRoute(['products/copy', 'id' => $this->title]), ['class' => 'btn btn-success btn-block', 'title' => 'Покупка']) ?>
            <?= Html::a('Со склада в зал', Url::toRoute(['products/copy', 'id' => $this->title]), ['class' => 'btn btn-primary btn-block', 'title' => 'Со склада в зал']) ?>
            <?= Html::a('Из зала на склад', Url::toRoute(['products/copy', 'id' => $this->title]), ['class' => 'btn btn-warning btn-block', 'title' => 'Из зала на склад']) ?>
        </div>
    </div>

</div>