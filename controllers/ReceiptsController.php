<?php

namespace app\controllers;

use Yii;
use app\models\Receipt;
use app\models\ReceiptItem;
use app\models\ReceiptSearch;
use app\models\ReceiptProductsSearch;
use app\models\Product;
use app\models\Amount;
use app\models\Operation;
use app\models\Size;
use app\models\Manager;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\Cors;

/**
 * ReceiptsController implements the CRUD actions for Receipt model.
 */
class ReceiptsController extends Controller
{
    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::className(),
            ],
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['add-item'],
                        'allow' => true,
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin', 'manager'],
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
        $searchModel = new ReceiptSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'sizes' => Size::find()->asArray()->all(),
            'managers' => Manager::find()->asArray()->all(),
            'itemsExt' => Receipt::getItemsExt(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProducts()
    {
        $searchModel = new ReceiptProductsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('products', [
            'sizes' => Size::find()->asArray()->all(),
            'managers' => Manager::find()->asArray()->all(),
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Receipt model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id, $first_load = false)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
            'first_load' => $first_load,
        ]);
    }

    /**
     * Creates a new Receipt model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $opened_receipt = Receipt::findOne(['is_closed' => 0]);
        if ($opened_receipt) return $this->redirect(['update', 'id' => $opened_receipt->id]);

        $receipt = new Receipt();
        $receipt->payment_type = 0;
        $receipt->total_amount = 0;
        $receipt->sale = 0;
        $receipt->price = 0;
        $receipt->total_price = 0;
        $receipt->change = 0;
        $receipt->manager_id = Yii::$app->user->id;
        $receipt->is_closed = 0;
        $receipt->save();

        return $this->redirect(['update', 'id' => $receipt->id]);
    }

    /**
     * Changes receipts manager.
     * @param integer $id
     * @param integer $manager_id
     * @return mixed
     */
    public function actionChangeManager($id, $manager_id)
    {
        $receipt = Receipt::findOne($id);
        if ($receipt) {
            $receipt->manager_id = $manager_id;
            $receipt->save();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Changes created_at time of receipt.
     * @param integer $id
     * @param integer $time
     * @return mixed
     */
    public function actionChangeTime($id, $time)
    {
        $receipt = Receipt::findOne($id);
        if ($receipt) {
            $receipt->created_at = $time;
            $receipt->save(false);
            foreach ($receipt->items as $receipt_item) {
                $receipt_item->created_at = $time;
                $receipt_item->save(false);
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSyncTime()
    {
        foreach (Receipt::find()->all() as $receipt) {
            foreach ($receipt->items as $receipt_item) {
                $receipt_item->created_at = $receipt->created_at;
                $receipt_item->save(false);
            }
        }
        return 'ready';
    }

    public function actionAddItem($barcode)
    {
        $product = Product::findByBarcode($barcode);
        if ($product !== null) {
            $last_receipt = Receipt::find()->orderBy(['id' => SORT_DESC])->one();
            if ($last_receipt !== null and !$last_receipt->is_closed) {
                $color = ltrim(substr($barcode, 8, 2), '0');
                $size = ltrim(substr($barcode, 10, 2), '0');
                $size_obj = Size::findOne($size);
                $amount = 1;

                $hall_query = [
                    'product_id' => $product->id,
                    'amount_type' => Amount::TYPE_HALL
                ];
                $warehouse_query = [
                    'product_id' => $product->id,
                    'amount_type' => Amount::TYPE_WAREHOUSE
                ];

                if ($size_obj) $hall_query['size_id'] = $size;
                if ($size_obj) $warehouse_query['size_id'] = $size;

                $amount_hall = Amount::find()->where($hall_query)->one();

                //Получаем наличие в зале
                if ($amount_hall !== null) {
                    //Проверяем достаточно ли в зале товара
                    if (!$amount_hall->amount) {
                        //Получаем наличие на складе
                        $amount_warehouse = Amount::find()->where($warehouse_query)->one();
                        if ($amount_warehouse !== null) {
                            //Проверяем достаточно ли в зале и на складе вместе
                            if (!$amount_warehouse->amount) {
                                //Недостаточно товара в зале и на складе
                                //Yii::$app->session->setFlash('receipt_add_item_error', 'Недостаточно товара в зале и на складе');
                                Yii::$app->session->setFlash('error', 'Недостаточно товара в зале и на складе');
                                return $this->redirect(Yii::$app->request->referrer);
                            }
                        } else {
                            //Yii::$app->session->setFlash('receipt_add_item_error', 'Товар не найден');
                            Yii::$app->session->setFlash('error', 'Товар не найден');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }
                } else {
                    $amount_warehouse = Amount::find()->where($warehouse_query)->one();
                    if ($amount_warehouse !== null) {
                        if (!$amount_warehouse->amount) {
                            //Недостаточно товара на складе
                            //Yii::$app->session->setFlash('receipt_add_item_error', 'Недостаточно товара на складе');
                            Yii::$app->session->setFlash('error', 'Недостаточно товара на складе');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    } else {
                        //Недостаточно товара в зале и на складе
                        //Yii::$app->session->setFlash('receipt_add_item_error', 'Недостаточно товара в зале и на складе');
                        Yii::$app->session->setFlash('error', 'Недостаточно товара в зале и на складе');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }

                $item = new ReceiptItem();
                $item->receipt_id = $last_receipt->id;
                $item->product_id = $product->id;
                if ($size_obj) {
                    $item->size_id = $size;
                }
                $item->amount = $amount;
                $item->price = $product->getActualPrice($size_obj);
                $item->total_price = $amount * $item->price;
                $item->created_at = $last_receipt->created_at;
                $item->save(false);

                $last_receipt->recalc();
            }
        }
        else {
            Yii::$app->session->setFlash('error', 'Товар не найден');
        }

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDeleteItem($receipt_id, $item_id) {
        $receipt = Receipt::findOne($receipt_id);
        $item = ReceiptItem::findOne($item_id);
        $item->delete();
        $receipt->recalc();
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
                $receipt->recalc();
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
                $receipt_item->total_price = 0;
            } else {
                $total_price = ($receipt_item->amount * $receipt_item->price) - $receipt_item->sale;
                $receipt_item->total_price = $total_price;
            }
            $receipt_item->gift = $is_gift;
            $receipt_item->save();
            $receipt->recalc();
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionSave($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
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
                                Yii::$app->session->setFlash('receipt_update_error', 'Недостаточно товара в зале и на складе');
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
                            Yii::$app->session->setFlash('receipt_update_error', 'Недостаточно товара на складе');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    } else {
                        //Недостаточно товара в зале и на складе
                        Yii::$app->session->setFlash('receipt_update_error', 'Недостаточно товара в зале и на складе');
                        return $this->redirect(Yii::$app->request->referrer);
                    }
                }
            }

            //Сохраняем чек и добавляем операцию в отчет
            if ($model->payment_type == Receipt::PAY_CASH) {
                $model->cash_total = $model->total_price;
                $model->nocash_total = 0;
            }
            if ($model->payment_type == Receipt::PAY_NOCASH) {
                $model->cash_total = 0;
                $model->nocash_total = $model->total_price;
            }
            if ($model->payment_type == Receipt::PAY_COMBO) {
                $model->nocash_total = $model->total_price - $model->cash_total;
            }
            $model->is_closed = 1;
            $model->save();

            //Рассчитываем закупочную цену
            $purchase_price = 0;
            foreach ($model->items as $receipt_item) {
                if ($receipt_item->size) {
                    if ($receipt_item->size->name < 50 and $receipt_item->product->purchase_price_small) {
                        $purchase_price += $receipt_item->product->purchase_price_small;
                    }
                    if ($receipt_item->size->name >= 50 and $receipt_item->product->purchase_price_big) {
                        $purchase_price += $receipt_item->product->purchase_price_big;
                    }
                }
                $purchase_price += $receipt_item->product->purchase_price;

                $receipt_item->created_at = $model->created_at;
                $receipt_item->save(false);
            }

            //Добавляем операцию в отчет
            if ($model->payment_type == Receipt::PAY_CASH or $model->payment_type == Receipt::PAY_NOCASH) {
                $operation = new Operation();
                $operation->name = 'Чек №'.$model->id;
                $operation->purchase_price = $purchase_price;
                $operation->type_id = Operation::TYPE_INCOME;
                $operation->cat_id = Operation::CAT_SELL;
                $operation->payment_type = $model->payment_type + 1;
                $operation->total_price = $model->total_price;
                $operation->user_id = $model->manager ? $model->manager->id : Yii::$app->user->id;
                if ($operation->save()) {
                    $operation->created_at = $model->created_at;
                    $operation->save();
                }
            }
            if ($model->payment_type == Receipt::PAY_COMBO) {
                $operation = new Operation();
                $operation->name = 'Чек №'.$model->id;
                $operation->purchase_price = $purchase_price;
                $operation->type_id = Operation::TYPE_INCOME;
                $operation->cat_id = Operation::CAT_SELL;
                $operation->payment_type = Operation::PAY_CASH;
                $operation->total_price = $model->cash_total;
                $operation->user_id = $model->manager ? $model->manager->id : Yii::$app->user->id;
                if ($operation->save()) {
                    $operation->created_at = $model->created_at;
                    $operation->save();
                }

                $operation = new Operation();
                $operation->name = 'Чек №'.$model->id;
                $operation->purchase_price = $purchase_price;
                $operation->type_id = Operation::TYPE_INCOME;
                $operation->cat_id = Operation::CAT_SELL;
                $operation->payment_type = Operation::PAY_NOCASH;
                $operation->total_price = $model->nocash_total;
                $operation->user_id = $model->manager ? $model->manager->id : Yii::$app->user->id;
                if ($operation->save()) {
                    $operation->created_at = $model->created_at;
                    $operation->save();
                }
            }

            return $this->redirect(['print-bill', 'id' => $model->id]);
        } else {
            return $this->redirect(Yii::$app->request->referrer);
        }
    }

    public function actionPrintBill($id) {
        return $this->render('bill', [
            'model' => $this->findModel($id),
        ]);
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
        $model->is_closed = 1;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if (Yii::$app->request->isAjax) {
                return $this->renderPartial('update', [
                    'model' => $model,
                ]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
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
        $receipt = $this->findModel($id);
        foreach ($receipt->items as $receipt_item) {
            $amount_obj = Amount::findOne(['product_id' => $receipt_item->product_id, 'size_id' => $receipt_item->size_id, 'amount_type' => Amount::TYPE_WAREHOUSE]);
            if ($amount_obj) {
                $amount_obj->amount += $receipt_item->amount;
                $amount_obj->save();
            }
            $receipt_item->delete();
        }
        $operations = Operation::find()->where(['name' => 'Чек №'.$id])->all();
        foreach ($operations as $operation) {
            $operation->delete();
        }
        $receipt->delete();
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
