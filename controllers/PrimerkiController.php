<?php

namespace app\controllers;

use Yii;
use app\models\Primerka;
use app\models\Client;
use app\models\PrimerkiSearch;
use app\models\PrimerkaWish;
use app\models\PrimerkaProduct;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * PrimerkiController implements the CRUD actions for Primerka model.
 */
class PrimerkiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin', 'manager'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Primerka models.
     * @return mixed
     */
    public function actionIndex($start = null)
    {
        if (!$start) $start = 'Monday this week';
        $begin = new \DateTime($start);
        $end = new \DateTime($start);
        $end = $end->modify('+1 week');
        $interval = \DateInterval::createFromDateString('1 day');
        $days = new \DatePeriod($begin, $interval, $end);
        //$hours = [11, 12, 13, 14, 15, 16, 17, 18, 19, 20];
        $hours = [12, 13, 14, 15, 16, 17, 18, 19];
        $primerki = Primerka::find()->where(['between', 'date', $begin->getTimestamp(), $end->getTimestamp()])->all();

        return $this->render('index', [
            'days' => $days,
            'hours' => $hours,
            'primerki' => $primerki,
        ]);
    }

    /**
     * Lists all Primerka models.
     * @return mixed
     */
    public function actionIndexTable()
    {
        $searchModel = new PrimerkiSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index-table', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Primerka model.
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
     * Creates a new Primerka model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($date = null)
    {
        $model = new Primerka();
        if ($date) $model->date = $date;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->date_field) $model->date = strtotime($model->date_field);
            if (!$model->newClient()) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($model->save()) {
                if ($model->wishes_field) {
                    foreach ($model->wishes_field as $product_id) {
                        $new_product = new PrimerkaWish();
                        $new_product->primerka_id = $model->id;
                        $new_product->product_id = $product_id;
                        $new_product->save();
                    }
                }
                if ($model->products_field) {
                    foreach ($model->products_field as $product_id) {
                        $new_product = new PrimerkaProduct();
                        $new_product->primerka_id = $model->id;
                        $new_product->product_id = $product_id;
                        $new_product->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Primerka model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->date_field) $model->date = strtotime($model->date_field);
            if (!$model->newClient()) {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
            if ($model->save()) {
                PrimerkaWish::deleteAll(['primerka_id' => $model->id]);
                if ($model->wishes_field) {
                    foreach ($model->wishes_field as $product_id) {
                        $new_product = new PrimerkaWish();
                        $new_product->primerka_id = $model->id;
                        $new_product->product_id = $product_id;
                        $new_product->save();
                    }
                }
                PrimerkaProduct::deleteAll(['primerka_id' => $model->id]);
                if ($model->products_field) {
                    foreach ($model->products_field as $product_id) {
                        $new_product = new PrimerkaProduct();
                        $new_product->primerka_id = $model->id;
                        $new_product->product_id = $product_id;
                        $new_product->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Primerka model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        PrimerkaWish::deleteAll(['primerka_id' => $model->id]);
        PrimerkaProduct::deleteAll(['primerka_id' => $model->id]);
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Primerka model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Primerka the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Primerka::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
