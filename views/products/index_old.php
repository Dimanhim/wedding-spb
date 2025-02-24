<?php
    use yii\helpers\Html;
    use himiklab\thumbnail\EasyThumbnailImage;
    use yii\helpers\Url;
    use kartik\date\DatePicker;
    use yii\widgets\LinkPager;
    use kartik\builder\Form;

    /* @var $this yii\web\View */
    /* @var $searchModel app\models\ProductSearch */
    /* @var $dataProvider yii\data\ActiveDataProvider */

    $this->title = $category->name;
    $template_folder = 'indexes';
    $type = Yii::$app->request->get('type');
    if ($type == 'hall') {
        $template_folder = 'halls';
        $this->title .= ' (в зале)';
    }
    if ($type == 'warehouse') {
        $template_folder = 'warehouses';
        $this->title .= ' (на складе)';
    }
    $this->params['breadcrumbs'][] = $this->title;
?>
<div class="product-index">
    <h1><?= Html::encode($this->title) ?></h1>

    <div class="search_block">
        <?php if ($template_folder != 'indexes'): ?>
            <?= Html::a('Все товары', ['index', 'category_id' => $category->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<span class="glyphicon glyphicon-print"></span>', 'javascript: print();', ['class' => 'btn btn-info pull-right', 'title' => 'Печать']) ?>
        <?php endif ?>

        <?php if ($template_folder == 'indexes'): ?>
            <?= Html::a('Фильтры', '#filter_block', ['class' => 'btn btn-primary', 'title' => 'Фильтры', 'data-toggle' => 'collapse']) ?>
        <?php endif ?>

        <?= Html::a('<span class="glyphicon glyphicon-shopping-cart"></span>', '#', ['class' => 'btn btn-info cart_link', 'title' => 'Корзина']) ?>
        <?= Html::a('Добавить товар', ['create', 'category_id' => $category->id], ['class' => 'btn btn-success']) ?>

        <?php if (Yii::$app->request->get('is_deleted') == 1): ?>
            <?= Html::a('Скрыть архив', ['index', 'category_id' => $category->id], ['class' => 'btn btn-warning']) ?>
        <?php else: ?>
            <?= Html::a('Показать архив', ['index', 'category_id' => $category->id, 'is_deleted' => 1], ['class' => 'btn btn-warning']) ?>
        <?php endif ?>

        <?= Html::a('В зале', ['index', 'category_id' => $category->id, 'type' => 'hall'], ['class' => 'btn btn-default']) ?>
        <?= Html::a('На складе', ['index', 'category_id' => $category->id, 'type' => 'warehouse'], ['class' => 'btn btn-default']) ?>

        <?= Html::a('Печать ШК', ['print-codes'], ['class' => 'btn btn-primary', 'id' => 'print_codes_btn']) ?>

        <?php if ($template_folder == 'indexes'): ?>
            <?= $this->render('_search', ['model' => $searchModel, 'category_id' => $category->id]); ?>
        <?php endif ?>
    </div>

    <?php
        if ($template_folder != 'indexes') {
            $marks_arr = [];
            $summary_str = 'всего <strong>'.$dataProvider->totalCount.'</strong>, из них ';
            foreach ($dataProvider->getModels() as $product) {
                $marks_arr[$product->marka->name] = isset($marks_arr[$product->marka->name]) ? $marks_arr[$product->marka->name] + 1 : 1;
            }
            foreach ($marks_arr as $key => $value) {
                $summary_str .= '<strong>'.$value.' <em>'.$key.'</em></strong>, ';
            }
            echo '<p class="summary">'.$summary_str.'</p>';
        }
    ?>

    <form action="" id="products_form">
        <?= 
            $this->render($template_folder.'/index_'.$category->type, [
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