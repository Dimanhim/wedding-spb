<?php

namespace app\controllers;

use Yii;
use app\models\WHMove;
use app\models\WHMovesItem;
use app\models\WHMoveSearch;
use app\models\Amount;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * WHMovesController implements the CRUD actions for WHMove model.
 */
class WhmovesController extends Controller
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
     * Lists all WHMove models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WHMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WHMove model.
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
     * Updates an existing WHMove model.
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
            $total_amount = 0;
            foreach ($order_items as $order_item) {
                $total_amount += $order_item['amount'];
            }
            $new_move = new WHMove();
            $new_move->total_amount = $total_amount;
            $new_move->status = 1;
            if ($new_move->save()) {
                foreach ($order_items as $move_item) {
                    $new_move_item = new WHMovesItem();
                    $new_move_item->move_id = $new_move->id;
                    $new_move_item->product_id = $move_item['product_id'];
                    $new_move_item->amount = $move_item['amount'];
                    $new_move_item->size_id = $move_item['size_id'];
                    $new_move_item->status = 1;
                    $new_move_item->save();
                }
            }
        }
        Yii::$app->session->setFlash('move_create_success', 'Перемещение успешно создано');
        return true;
    }

    public function actionItemsUpdate($id) {
        $move = $this->findModel($id);
        $post = Yii::$app->request->post();

        foreach ($post['move_items'] as $move_item_id => $move_item_data) {
            if (($move_item = WHMovesItem::findOne($move_item_id)) !== null) {
                //Если статус частично пришел
                if ($move_item_data['status'] == WHMovesItem::MOVE_PART) {
                    $arrived = (isset($move_item_data['arrived'])) ? $move_item_data['arrived'] : 0;
                    $old_arrived = $move_item->arrived ? $move_item->arrived : 0;

                    //Если пришло больше или равно общему кол-ву, то считаем, что пришло всё
                    if ($arrived >= $move_item->amount) {
                        $move_item->status = WHMovesItem::MOVE_FULL;
                        $move_item->arrived = $move_item->amount;
                    } else {
                        $move_item->status = WHMovesItem::MOVE_PART;
                        $move_item->arrived = $move_item_data['arrived'];
                    }
                    $move->status = WHMove::MOVE_PART;

                    //Меняем кол-во на складе и в зале
                    $diff_arived = $move_item->arrived - $old_arrived;
                    $move->moveItem($move_item, Amount::TYPE_HALL, $diff_arived, true);
                    $move->moveItem($move_item, Amount::TYPE_WAREHOUSE, $diff_arived, false);

                    //Ставим дату продажи
                    $move_item->product->sell_date = time();
                    $move_item->product->save();

                } elseif ($move_item_data['status'] == WHMovesItem::MOVE_FULL) {
                    $old_arrived = $move_item->arrived ? $move_item->arrived : 0;
                    
                    $move_item->status = WHMovesItem::MOVE_FULL;
                    $move_item->arrived = $move_item->amount;
                    $move->status = WHMove::MOVE_PART;

                    //Меняем кол-во на складе и в зале
                    $diff_arived = $move_item->arrived - $old_arrived;
                    $move->moveItem($move_item, Amount::TYPE_HALL, $diff_arived, true);
                    $move->moveItem($move_item, Amount::TYPE_WAREHOUSE, $diff_arived, false);

                    //Ставим дату продажи
                    $move_item->product->sell_date = time();
                    $move_item->product->save();
                } else {
                    continue;
                }
                $move_item->save();
            }
        }

        //Если все товары пришли, то меняем статус у всего заказа
        $is_move_full = true;
        foreach ($move->items as $move_item) {
            if ($move_item->status != WHMovesItem::MOVE_FULL)
                $is_move_full = false;
        }
        if ($is_move_full) {
            $move->status = WHMove::MOVE_FULL;
        }
        $move->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing WHMove model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        WHMovesItem::deleteAll(['move_id' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the WHMove model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WHMove the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WHMove::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
