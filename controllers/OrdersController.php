<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\Order;
use app\models\OrderSearch;
use app\models\OrderItem;
use app\models\Amount;
use app\models\Operation;
use app\models\Mark;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * OrdersController implements the CRUD actions for Order model.
 */
class OrdersController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Order models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'marks' => Mark::find()->asArray()->all(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Order model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Updates an existing Order model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


    public function actionCreate()
    {
        $post = Yii::$app->request->post();
        if (isset($post['order_items']) and $order_items = $post['order_items']) {
            $await_date = $post['await_date'] ? strtotime($post['await_date']) : time();

            //Вычисляем общее кол-во и стоимость заказа
            $total_amount = 0;
            $total_price = 0;
            foreach ($order_items as $order_item) {
                $total_amount += $order_item['amount'];
                $total_price += $order_item['price'];
            }

            //Создаём заказ
            $new_order = new Order();
            $new_order->await_date = $await_date ? $await_date : time();
            $new_order->payment_type = 1;
            $new_order->total_amount = $total_amount;
            $new_order->total_price = $total_price;
            $new_order->total_payed = 0;
            $new_order->total_rest = $total_price;
            $new_order->accepted = 0;
            $new_order->payment_status = Order::PAYMENT_INIT;
            $new_order->delivery_status = Order::DELIVERY_INIT;
            if ($new_order->save()) {
                foreach ($order_items as $order_item) {
                    $product = Product::findOne($order_item['product_id']);

                    //Создаём товар в заказе
                    $new_order_item = new OrderItem();
                    $new_order_item->order_id = $new_order->id;
                    $new_order_item->product_id = $order_item['product_id'];
                    $new_order_item->marka_id = $product->marka_id;
                    $new_order_item->amount = $order_item['amount'];
                    $new_order_item->price = $order_item['price'];
                    $new_order_item->size_id = $order_item['size_id'];
                    $new_order_item->delivery_status = OrderItem::DELIVERY_INIT;
                    $new_order_item->save();

                    //Ставим дату продажи
                    $product->purchase_date = time();
                    $product->save();
                }
            }
        }
        Yii::$app->session->setFlash('order_create_success', 'Заказ успешно создан');
        return true;
    }

    public function actionPay($id) {
        $order = $this->findModel($id);
        $post = Yii::$app->request->post();
        $payed = (isset($post['Order']['total_payed'])) ? $post['Order']['total_payed'] : 0;
        if ($payed) {
            $total_payed = $order->total_payed + $payed;
            if ($total_payed >= $order->total_price) {
                //Полностью оплачен
                $order->total_payed = $order->total_price;
                $order->total_rest = 0;
                $order->payment_status = Order::PAYMENT_FULL;
            } else {
                //Частично оплачен
                $order->total_payed = $total_payed;
                $order->total_rest = $order->total_price - $order->total_payed;
                $order->payment_status = Order::PAYMENT_PART;
            }
            $order->payment_type = $post['Order']['payment_type'];
            $order->acceptOrder();
            $order->save();

            //Добавляем операцию в отчет
            $operation = new Operation();
            $operation->name = 'Частичная оплата заказа №'.$order->id;
            $operation->purchase_price = 0;
            $operation->type_id = Operation::TYPE_EXPENSE;
            $operation->cat_id = Operation::CAT_BUY;
            $operation->payment_type = $post['Order']['payment_type'];
            $operation->total_price = $payed;
            $operation->user_id = Yii::$app->user->id;
            $operation->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionFullPay($id) {
        $post = Yii::$app->request->post();

        $order = $this->findModel($id);
        $old_payed = $order->total_payed;

        $order->total_payed = $order->total_price;
        $order->total_rest = 0;
        $order->payment_type = $post['Order']['payment_type'];
        $order->payment_status = Order::PAYMENT_FULL;
        $order->acceptOrder();
        $order->save();

        //Добавляем операцию в отчет
        $operation = new Operation();
        $operation->name = 'Оплата заказа №'.$order->id;
        $operation->purchase_price = 0;
        $operation->type_id = Operation::TYPE_EXPENSE;
        $operation->cat_id = Operation::CAT_BUY;
        $operation->payment_type = $post['Order']['payment_type'];
        $operation->total_price = ($order->total_price - $old_payed);
        $operation->user_id = Yii::$app->user->id;
        $operation->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionItemsUpdate($id) {
        $order = $this->findModel($id);
        $post = Yii::$app->request->post();

        foreach ($post['order_items'] as $order_item_id => $order_item_data) {
            if (($order_item = OrderItem::findOne($order_item_id)) !== null) {
                //Если статус частично пришел
                if ($order_item_data['delivery_status'] == OrderItem::DELIVERY_PART) {
                    $arrived = (isset($order_item_data['arrived'])) ? $order_item_data['arrived'] : 0;
                    $old_arrived = $order_item->arrived ? $order_item->arrived : 0;

                    //Если пришло больше или равно общему кол-ву, то считаем, что пришло всё
                    if ($arrived >= $order_item->amount) {
                        $order_item->delivery_status = OrderItem::DELIVERY_FULL;
                        $order_item->arrived = $order_item->amount;
                    } else {
                        $order_item->delivery_status = OrderItem::DELIVERY_PART;
                        $order_item->arrived = $order_item_data['arrived'];
                    }
                    $order->delivery_status = Order::DELIVERY_PART;

                    //Меняем кол-во на складе и в ожидании
                    $diff_arived = $order_item->arrived - $old_arrived;
                    $order->acceptOrderItem($order_item, Amount::TYPE_WAREHOUSE, $diff_arived, true);
                    $order->acceptOrderItem($order_item, Amount::TYPE_WAIT, $diff_arived, false);

                //Если статус полностью пришел
                } elseif ($order_item_data['delivery_status'] == OrderItem::DELIVERY_FULL) {
                    $old_arrived = $order_item->arrived ? $order_item->arrived : 0;
                    
                    $order_item->delivery_status = OrderItem::DELIVERY_FULL;
                    $order_item->arrived = $order_item->amount;
                    $order->delivery_status = Order::DELIVERY_PART;

                    //Меняем кол-во на складе и в ожидании
                    $diff_arived = $order_item->arrived - $old_arrived;
                    $order->acceptOrderItem($order_item, Amount::TYPE_WAREHOUSE, $diff_arived, true);
                    $order->acceptOrderItem($order_item, Amount::TYPE_WAIT, $diff_arived, false);

                } else {
                    continue;
                }
                $order_item->save();
            }
        }

        //Если все товары пришли, то меняем статус у всего заказа
        $is_delivery_full = true;
        foreach ($order->items as $order_item) {
            if ($order_item->delivery_status != OrderItem::DELIVERY_FULL)
                $is_delivery_full = false;
        }
        if ($is_delivery_full) {
            $order->delivery_status = Order::DELIVERY_FULL;
        }
        $order->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeliveryFull($id) {
        $order = $this->findModel($id);
        foreach ($order->items as $order_item) {
            $old_arrived = $order_item->arrived ? $order_item->arrived : 0;
                    
            $order_item->delivery_status = OrderItem::DELIVERY_FULL;
            $order_item->arrived = $order_item->amount;
            $order->delivery_status = Order::DELIVERY_PART;

            //Меняем кол-во на складе и в ожидании
            $diff_arived = $order_item->arrived - $old_arrived;
            $order->acceptOrderItem($order_item, Amount::TYPE_WAREHOUSE, $diff_arived, true);
            $order->acceptOrderItem($order_item, Amount::TYPE_WAIT, $diff_arived, false);
            $order_item->save();
        }
        $order->delivery_status = Order::DELIVERY_FULL;
        $order->save();
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Order model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        OrderItem::deleteAll(['product_id' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the Order model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Order the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Order::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
