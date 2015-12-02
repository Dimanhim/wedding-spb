<?php

namespace app\controllers;

use Yii;
use app\models\HWMove;
use app\models\HWMovesItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HWMovesController implements the CRUD actions for HWMove model.
 */
class HwmovesController extends Controller
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
     * Lists all HWMove models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => HWMove::find(),
        ]);

        return $this->render('index', [
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

    /**
     * Deletes an existing HWMove model.
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
