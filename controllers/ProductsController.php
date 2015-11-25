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

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller
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
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex($category_id)
    {
        $category = Category::findOne($category_id);
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index_'.$category->type, [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
        ]);
    }

    /**
     * Displays a single Product model.
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
            $model->photo = UploadedFile::getInstance($model, 'photo');
            if ($model->photo) {
                $image_path = '/web/files/'.time().'.'. $model->photo->extension;
                Yii::info(\Yii::$app->basePath.$image_path);
                $model->photo->saveAs(\Yii::$app->basePath.$image_path);
                $model->photo = $image_path;
            }

            if ($model->save()) {
                //Сохранение кол-ва
                foreach ($post['Product']['amount'] as $size_id => $size_vals) {
                    foreach ($size_vals as $key => $value) {
                        $new_amount = new Amount();
                        $new_amount->product_id = $model->id;
                        $new_amount->size_id = $size_id;
                        $new_amount->amount_type = $key;
                        $new_amount->amount = $value;
                        $new_amount->save();
                    }
                }
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::info(print_r($model->getErrors(), true));
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'category' => $category,
            ]);
        }
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
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
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
