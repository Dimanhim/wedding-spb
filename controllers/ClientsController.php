<?php

namespace app\controllers;

use Yii;
use app\models\Client;
use app\models\Primerka;
use app\models\ClientsSearch;
use app\models\ClientProduct;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;

/**
 * ClientsController implements the CRUD actions for Client model.
 */
class ClientsController extends Controller
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
     * Lists all Client models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClientsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Client model.
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
     * Creates a new Client model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Client();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->first_visit_field) $model->first_visit = strtotime($model->first_visit_field);
            if ($model->birtday_field) $model->birtday = strtotime($model->birtday_field);
            if ($model->event_date_field) $model->event_date = strtotime($model->event_date_field);
            if ($model->sizes_field) $model->sizes = implode(',', $model->sizes_field);
            if ($model->save()) {
                if ($model->products_field) {
                    foreach ($model->products_field as $product_id) {
                        $new_product = new ClientProduct();
                        $new_product->client_id = $model->id;
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
     * Updates an existing Client model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->first_visit_field) $model->first_visit = strtotime($model->first_visit_field);
            if ($model->birtday_field) $model->birtday = strtotime($model->birtday_field);
            if ($model->event_date_field) $model->event_date = strtotime($model->event_date_field);
            if ($model->sizes_field) $model->sizes = implode(',', $model->sizes_field);
            if ($model->save()) {
                ClientProduct::deleteAll(['client_id' => $model->id]);
                if ($model->products_field) {
                    foreach ($model->products_field as $product_id) {
                        $new_product = new ClientProduct();
                        $new_product->client_id = $model->id;
                        $new_product->product_id = $product_id;
                        $new_product->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Client model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        ClientProduct::deleteAll(['client_id' => $model->id]);
        Primerka::deleteAll(['client_id' => $model->id]);
        $model->delete();
        return $this->redirect(['index']);
    }

    // public function actionTablet($id)
    // {
    //     $model = $this->findModel($id);
    //     return $this->render('view', [
    //         'model' => $this->findModel($id),
    //     ]);
    // }

    /**
     * Finds the Client model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Client the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Client::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
