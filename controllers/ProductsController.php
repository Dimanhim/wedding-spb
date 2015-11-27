<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Mark;
use app\models\Model;
use app\models\Color;
use app\models\Rate;
use app\models\Amount;
use app\models\Size;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller
{
    public function behaviors()
    {
        return [

        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex($category_id)
    {
        $category = Category::findOne($category_id);
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'sizes' => Size::find()->all(),
        ]);
    }

    /**
     * Displays a single Product model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'category' => $model->category,
        ]);
    }

    /**
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($category_id)
    {
        $model = new Product();
        $category = Category::findOne($category_id);
        $model->scenario = 'type_'.$category->type;
        $model->category_id = $category_id;

        $post = Yii::$app->request->post();
        if ($model->load($post)) {

            //Добавление новых позиций в справочники
            if ($model->marka_new) {
                $new_mark = new Mark();
                $new_mark->name = $model->marka_new;
                if ($new_mark->save()) {
                    $model->marka_id = $new_mark->id;
                }
            }
            if ($model->model_new) {
                $new_model = new Model();
                $new_model->name = $model->model_new;
                if ($new_model->save()) {
                    $model->model_id = $new_model->id;
                }
            }
            if ($model->color_new) {
                $new_color = new Color();
                $new_color->name = $model->color_new;
                if ($new_color->save()) {
                    $model->color_id = $new_color->id;
                }
            }
            if ($model->ratio_new) {
                $new_ratio = new Rate();
                $new_ratio->name = $model->ratio_new;
                if ($new_ratio->save()) {
                    $model->ratio_id = $new_ratio->id;
                }
            }

            //Расчет рекомендуемых цен
            $curr_rate = Rate::findOne($model->ratio_id)->name;
            $model->recommended_price_small = $model->purchase_price_small * $curr_rate;
            $model->recommended_price_big = $model->purchase_price_big * $curr_rate;

            //Загрузка фото
            $model->photo_file = UploadedFile::getInstance($model, 'photo');
            if ($model->photo_file) {
                $image_path = '/web/files/'.time().'.'. $model->photo_file->extension;
                $model->photo_file->saveAs(\Yii::$app->basePath.$image_path);
                $model->photo = $image_path;
            }

            if ($model->save()) {
                //Сохранение кол-ва
                if (isset($post['Product']['amount'])) {
                    foreach ($post['Product']['amount'] as $amount_key => $amount_value) {
                        if (is_array($amount_value)) {
                            foreach ($amount_value as $key => $value) {
                                $new_amount = new Amount();
                                $new_amount->product_id = $model->id;
                                $new_amount->size_id = $amount_key;
                                $new_amount->amount_type = $key;
                                $new_amount->amount = $value;
                                $new_amount->save();
                            }
                        } else {
                            $new_amount = new Amount();
                            $new_amount->product_id = $model->id;
                            $new_amount->amount_type = $amount_key;
                            $new_amount->amount = $amount_value;
                            $new_amount->save();
                        }
                    }
                }
                return $this->redirect(['index', 'category_id' => $model->category->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
            ]);
        }
    }

    public function actionCopy($id)
    {
        $model = $this->findModel($id);
        $clone = new Product();
        $clone->scenario = 'type_'.$model->category->type;
        $clone->attributes = $model->attributes;

        //Дублирование фото
        if ($model->photo) {
            $new_filename = '/web/files/'.time().'.'. pathinfo($model->photo, PATHINFO_EXTENSION);
            if (copy(\Yii::$app->basePath.$model->photo, \Yii::$app->basePath.$new_filename)) {
                $clone->photo = $new_filename;
            }
        }

        if ($clone->save()) {
            //Сохранение кол-ва
            foreach (Amount::find()->where(['product_id' => $id])->all() as $amount_obj) {
                $new_amount = new Amount();
                $new_amount->attributes = $amount_obj->attributes;
                $new_amount->product_id = $clone->id;
                $new_amount->save();
            }
        } else {
            Yii::info(print_r($clone->getErrors(), true));
        }

        return $this->redirect(['index', 'category_id' => $model->category->id]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'type_'.$model->category->type;
        $old_photo = $model->photo;
        $post = Yii::$app->request->post();
        if ($model->load($post)) {

            //Добавление новых позиций в справочники
            if ($model->marka_new) {
                $new_mark = new Mark();
                $new_mark->name = $model->marka_new;
                if ($new_mark->save()) {
                    $model->marka_id = $new_mark->id;
                }
            }
            if ($model->model_new) {
                $new_model = new Model();
                $new_model->name = $model->model_new;
                if ($new_model->save()) {
                    $model->model_id = $new_model->id;
                }
            }
            if ($model->color_new) {
                $new_color = new Color();
                $new_color->name = $model->color_new;
                if ($new_color->save()) {
                    $model->color_id = $new_color->id;
                }
            }
            if ($model->ratio_new) {
                $new_ratio = new Rate();
                $new_ratio->name = $model->ratio_new;
                if ($new_ratio->save()) {
                    $model->ratio_id = $new_ratio->id;
                }
            }

            //Расчет рекомендуемых цен
            $curr_rate = Rate::findOne($model->ratio_id)->name;
            $model->recommended_price_small = $model->purchase_price_small * $curr_rate;
            $model->recommended_price_big = $model->purchase_price_big * $curr_rate;

            //Загрузка фото
            $model->photo_file = UploadedFile::getInstance($model, 'photo');
            if ($model->photo_file) {
                $image_path = '/web/files/'.time().'.'. $model->photo_file->extension;
                $model->photo_file->saveAs(\Yii::$app->basePath.$image_path);
                $model->photo = $image_path;
                //Удаление старого фото
                if ($old_photo) unlink(\Yii::$app->basePath.$old_photo);
            } else {
                $model->photo = $old_photo;
            }

            if ($model->save()) {
                //Удаление старого кол-ва
                Amount::deleteAll(['product_id' => $id]);
                //Сохранение кол-ва
                if (isset($post['Product']['amount'])) {
                    foreach ($post['Product']['amount'] as $amount_key => $amount_value) {
                        if (is_array($amount_value)) {
                            foreach ($amount_value as $key => $value) {
                                $new_amount = new Amount();
                                $new_amount->product_id = $model->id;
                                $new_amount->size_id = $amount_key;
                                $new_amount->amount_type = $key;
                                $new_amount->amount = $value;
                                $new_amount->save();
                            }
                        } else {
                            $new_amount = new Amount();
                            $new_amount->product_id = $model->id;
                            $new_amount->amount_type = $amount_key;
                            $new_amount->amount = $amount_value;
                            $new_amount->save();
                        }
                    }
                }
                return $this->redirect(['index', 'category_id' => $model->category->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'category' => $model->category,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $old_photo = $model->photo;
        $model->delete();
        //Удаление кол-ва
        Amount::deleteAll(['product_id' => $id]);
        //Удаление старого фото
        if ($old_photo) unlink(\Yii::$app->basePath.$old_photo);

        return $this->redirect(['index', 'category_id' => $model->category->id]);
    }

    /**
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
