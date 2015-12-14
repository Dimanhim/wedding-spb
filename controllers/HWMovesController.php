<?php

namespace app\controllers;

use Yii;
use app\models\HWMove;
use app\models\HWMovesItem;
use app\models\HWMoveSearch;
use app\models\Amount;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * HWMovesController implements the CRUD actions for HWMove model.
 */
class HwmovesController extends Controller
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
     * Lists all HWMove models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new HWMoveSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single HWMove model.
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
     * Updates an existing HWMove model.
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
            $new_move = new HWMove();
            $new_move->total_amount = $total_amount;
            $new_move->status = 1;
            if ($new_move->save()) {
                foreach ($order_items as $move_item) {
                    $new_move_item = new HWMovesItem();
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
            if (($move_item = HWMovesItem::findOne($move_item_id)) !== null) {
                //Если статус частично пришел
                if ($move_item_data['status'] == HWMovesItem::MOVE_PART) {
                    $arrived = (isset($move_item_data['arrived'])) ? $move_item_data['arrived'] : 0;
                    $old_arrived = $move_item->arrived ? $move_item->arrived : 0;

                    //Если пришло больше или равно общему кол-ву, то считаем, что пришло всё
                    if ($arrived >= $move_item->amount) {
                        $move_item->status = HWMovesItem::MOVE_FULL;
                        $move_item->arrived = $move_item->amount;
                    } else {
                        $move_item->status = HWMovesItem::MOVE_PART;
                        $move_item->arrived = $move_item_data['arrived'];
                    }
                    $move->status = HWMove::MOVE_PART;

                    //Меняем кол-во в зале и на складе
                    $diff_arived = $move_item->arrived - $old_arrived;
                    $move->moveItem($move_item, Amount::TYPE_WAREHOUSE, $diff_arived, true);
                    $move->moveItem($move_item, Amount::TYPE_HALL, $diff_arived, false);

                } elseif ($move_item_data['status'] == HWMovesItem::MOVE_FULL) {
                    $old_arrived = $move_item->arrived ? $move_item->arrived : 0;
                    
                    $move_item->status = HWMovesItem::MOVE_FULL;
                    $move_item->arrived = $move_item->amount;
                    $move->status = HWMove::MOVE_PART;

                    //Меняем кол-во в зале и на складе
                    $diff_arived = $move_item->arrived - $old_arrived;
                    $move->moveItem($move_item, Amount::TYPE_WAREHOUSE, $diff_arived, true);
                    $move->moveItem($move_item, Amount::TYPE_HALL, $diff_arived, false);

                } else {
                    continue;
                }
                $move_item->save();
            }
        }

        //Если все товары пришли, то меняем статус у всего заказа
        $is_move_full = true;
        foreach ($move->items as $move_item) {
            if ($move_item->status != HWMovesItem::MOVE_FULL)
                $is_move_full = false;
        }
        if ($is_move_full) {
            $move->status = HWMove::MOVE_FULL;
        }
        $move->save();

        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing HWMove model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        HWMovesItem::deleteAll(['move_id' => $id]);
        return $this->redirect(['index']);
    }

    /**
     * Finds the HWMove model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return HWMove the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = HWMove::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
