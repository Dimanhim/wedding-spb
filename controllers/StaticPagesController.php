<?php

namespace app\controllers;

use Yii;
use app\models\StaticPage;
use app\models\StaticPagesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * StaticPagesController implements the CRUD actions for StaticPage model.
 */
class StaticPagesController extends Controller
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
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all StaticPage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new StaticPagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaticPage model.
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
     * Creates a new StaticPage model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new StaticPage();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $image = UploadedFile::getInstance($model, 'slider_image_field');
            if ($image) {
                $image_path = '/files/'.time().'.'. $image->extension;
                $image->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->slider_image = $image_path;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing StaticPage model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_image = $model->slider_image;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $image = UploadedFile::getInstance($model, 'slider_image_field');
            if ($image) {
                $image_path = '/files/'.time().'.'. $image->extension;
                $image->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->slider_image = $image_path;
                if ($old_image and file_exists(\Yii::$app->basePath.'/public_html'.$old_image)) 
                    unlink(\Yii::$app->basePath.'/public_html'.$old_image);
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionDeleteSliderImage($id)
    {
        $model = $this->findModel($id);
        if ($model->slider_image and file_exists(Yii::$app->basePath.'/public_html'.$model->slider_image)) {
            unlink(Yii::$app->basePath.'/public_html'.$model->slider_image);
            $model->slider_image = NULL;
            $model->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing StaticPage model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->slider_image and file_exists(Yii::$app->basePath.'/public_html'.$model->slider_image)) {
            unlink(Yii::$app->basePath.'/public_html'.$model->slider_image);
        }
        $model->delete();
        return $this->redirect(['index']);
    }

    /**
     * Finds the StaticPage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return StaticPage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = StaticPage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
