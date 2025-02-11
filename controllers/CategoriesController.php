<?php

namespace app\controllers;

use Yii;
use app\models\Category;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\web\UploadedFile;

/**
 * CategoriesController implements the CRUD actions for Category model.
 */
class CategoriesController extends Controller
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $image = UploadedFile::getInstance($model, 'image_field');
            if ($image) {
                $image_path = '/files/'.time().'.'. $image->extension;
                $image->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->image = $image_path;
            }
            if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_image = $model->image;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $image = UploadedFile::getInstance($model, 'image_field');
            if ($image) {
                $image_path = '/files/'.time().'.'. $image->extension;
                $image->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->image = $image_path;
                //Удаление старого фото
                if ($old_image and file_exists(\Yii::$app->basePath.'/public_html'.$old_image)) 
                    unlink(\Yii::$app->basePath.'/public_html'.$old_image);
            }
            if ($model->save()) return $this->redirect(['view', 'id' => $model->id]);
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDeleteImage($id)
    {
        $model = $this->findModel($id);
        if ($model->image and file_exists(Yii::$app->basePath.'/public_html'.$model->image)) {
            unlink(Yii::$app->basePath.'/public_html'.$model->image);
            $model->image = NULL;
            $model->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
