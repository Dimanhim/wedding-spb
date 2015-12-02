<?php

namespace app\controllers;

use Yii;
use app\models\Order;
use app\models\OrderItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * OrdersController implements the CRUD actions for Order model.
 */
class OrdersController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
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
        $dataProvider = new ActiveDataProvider([
            'query' => Order::find(),
        ]);

        return $this->render('index', [
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
            $total_amount = 0;
            $total_price = 0;
            foreach ($order_items as $order_item) {
                $total_amount += $order_item['amount'];
                $total_price += $order_item['price'];
            }
            $new_order = new Order();
            $new_order->await_date = $await_date ? $await_date : time();
            $new_order->payment = 1;
            $new_order->total_amount = $total_amount;
            $new_order->total_price = $total_price;
            $new_order->total_payed = 0;
            $new_order->total_rest = $total_price;
            $new_order->status = 1;
            if ($new_order->save()) {
                foreach ($order_items as $order_item) {
                    $new_order_item = new OrderItem();
                    $new_order_item->order_id = $new_order->id;
                    $new_order_item->product_id = $order_item['product_id'];
                    $new_order_item->amount = $order_item['amount'];
                    $new_order_item->price = $order_item['price'];
                    $new_order_item->size_id = $order_item['size_id'];
                    $new_order_item->status = 1;
                    $new_order_item->save();
                }
            }
        }
        Yii::$app->session->setFlash('order_create_success', 'Заказ успешно создан');
        return true;
    }


    public function actionChangeStatus($id)
    {
        $order = $this->findModel($id);
        $post = Yii::$app->request->post();
        $old_status = $order->status;
        $new_status = (isset($post['Order']['status'])) ? $post['Order']['status'] : 0;

        //Только если статус изменился
        if ($new_status != $old_status) {
            //Только для оплаченных или пришедших заказов меняем наличие у товаров
            if ($new_status == Order::STATUS_FULL_COME or $old_status == Order::STATUS_PART_PAYED or $old_status == Order::STATUS_PAYED) {
                //Но если не удалось обновить наличие у товаров данного заказа, то редирект
                if (!Order::updateAmountByOrderStatus($order, $new_status)) {
                    return $this->redirect(Yii::$app->request->referrer);
                }
            } else {
                foreach ($order->items as $order_item) {
                    $order_item->status = $new_status;
                    $order_item->save();
                }
            }

            $order->status = $new_status;
            $order->save();
        }

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
