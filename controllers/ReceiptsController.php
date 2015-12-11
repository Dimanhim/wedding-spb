<?php

namespace app\controllers;

use Yii;
use app\models\Receipt;
use app\models\ReceiptItem;
use app\models\Product;
use app\models\Amount;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ReceiptsController implements the CRUD actions for Receipt model.
 */
class ReceiptsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Receipt models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Receipt::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Receipt model.
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
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $receipt = new Receipt();
        $receipt->payment_type = 0;
        $receipt->total_amount = 0;
        $receipt->sale = 0;
        $receipt->price = 0;
        $receipt->total_price = 0;
        $receipt->change = 0;
        $receipt->manager_id = rand(1, 13);
        $receipt->save();

        return $this->redirect(['update', 'id' => $receipt->id]);
    }

    public function actionAddItem($id)
    {
        $amount = rand(1, 3);
        $product_id = rand(1, 99);
        $product = Product::findOne($product_id);
        $amount_obj = Amount::find()->where(['product_id' => $product_id])->orderBy('RAND()')->one();

        $item = new ReceiptItem();
        $item->receipt_id = $id;
        $item->product_id = $product_id;
        $item->size_id = $amount_obj->size_id;
        $item->amount = $amount;
        $item->price = $product->price_small;
        $item->total_price = $amount * $product->price_small;
        $item->save();

        $receipt = $this->findModel($id);
        $receipt->total_amount += $item->amount;
        $receipt->price += $item->total_price;
        $receipt->total_price += $item->total_price;
        $receipt->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionAddSale($receipt_id)
    {
        $post = Yii::$app->request->post();
        if (($receipt_item = ReceiptItem::findOne($post['sale']['product'])) !== null) {
            $receipt_item->sale = $post['sale']['amount'];
            $receipt_item->total_price -= $receipt_item->sale;
            if ($receipt_item->save()) {
                $receipt = $this->findModel($receipt_id);
                $receipt->sale += $receipt_item->sale;
                $receipt->total_price -= $receipt_item->sale;
                $receipt->save();
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionMakeGift($receipt_id, $item_id)
    {
        $post = Yii::$app->request->post();
        $is_gift = $post['ReceiptItem']['gift'];
        $receipt = $this->findModel($receipt_id);
        if (($receipt_item = ReceiptItem::findOne($item_id)) !== null) {
            if ($is_gift) {
                $receipt->total_price -= $receipt_item->total_price;
                $receipt_item->total_price = 0;
            } else {
                $total_price = ($receipt_item->amount * $receipt_item->price) - $receipt_item->sale;
                $receipt->total_price += $total_price;
                $receipt_item->total_price = $total_price;
            }
            $receipt_item->gift = $is_gift;
            $receipt_item->save();
            $receipt->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSave($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //Меняем кол-во в зале
            foreach ($model->items as $receipt_item) {
                //Получаем наличие в зале
                $amount_query = ['product_id' => $receipt_item->product_id, 'amount_type' => Amount::TYPE_HALL];
                if ($receipt_item->size_id) $amount_query['size_id'] = $receipt_item->size_id;
                if (($amount = Amount::find()->where($amount_query)->one()) !== null) {
                    //Проверяем достаточно ли в зале товара
                    if ($amount->amount >= $receipt_item->amount) {
                        $amount->amount -= $receipt_item->amount;
                    } else {
                        //Получаем наличие на складе
                        $amount_query2 = ['product_id' => $receipt_item->product_id, 'amount_type' => Amount::TYPE_WAREHOUSE];
                        if ($receipt_item->size_id) $amount_query2['size_id'] = $receipt_item->size_id;
                        if (($amount2 = Amount::find()->where($amount_query2)->one()) !== null) {
                            //Проверяем достаточно ли в зале и на складе вместе
                            if (($amount->amount + $amount2->amount) >= $receipt_item->amount) {
                                $amount->amount = 0;
                                $remain = $receipt_item->amount - $amount->amount;
                                $amount2->amount -= $remain;
                                $amount2->save();
                            } else {
                                //Недостаточно товара в зале и на складе
                                return $this->redirect(Yii::$app->request->referrer);
                            }
                        }
                    }
                    $amount->save();
                } else {
                    $amount_query2 = ['product_id' => $receipt_item->product_id, 'amount_type' => Amount::TYPE_WAREHOUSE];
                    if ($receipt_item->size_id) $amount_query['size_id'] = $receipt_item->size_id;
                    if (($amount = Amount::find()->where($amount_query)->one()) !== null) {
                        if ($amount->amount >= $receipt_item->amount) {
                            $amount->amount -= $receipt_item->amount;
                        } else {
                            //Недостаточно товара на складе
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    } else {
                        //Недостаточно товара в зале и на складе
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    /**
     * Updates an existing Receipt model.
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

    /**
     * Deletes an existing Receipt model.
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
     * Finds the Receipt model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Receipt the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Receipt::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
