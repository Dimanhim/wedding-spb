<?php

namespace app\controllers;

use Yii;
use app\models\Product;
use app\models\ProductSearch;
use app\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;
use app\models\Mark;
use app\models\Model;
use app\models\Color;
use app\models\Rate;
use app\models\Amount;
use app\models\Size;
use app\models\ReceiptItem;
use app\models\ProductPriceCategory;
use app\models\ProductFashion;
use app\models\ProductFeature;
use app\models\ProductOccasion;
use yii\data\Sort;
use himiklab\thumbnail\EasyThumbnailImage;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller
{
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

    public function actionRename() {
        foreach (Product::find()->where(['name' => null])->all() as $product) {
            //$product->scenario = 'type_7';
            $product_name = '';
            if ($product->marka) $product_name .= $product->marka->name.' ';
            if ($product->model) $product_name .= $product->model->name.' ';
            if ($product->color) $product_name .= $product->color->name.' ';
            $product->name = trim($product_name);
            $product->save();
        }
    }

    public function actionResetStat() {
        foreach (Product::find()->all() as $product) {
            $product->total_show = 0;
            $product->total_like = 0;
            $product->save(false);
        }
        return 'Счетчики просмотров и лайков сброшены';
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex($category_id)
    {
        $sort = new Sort([
            'attributes' => [
                'position' => ['label' => 'Сортировать по позиции'],
                'model_id' => ['label' => 'Сортировать по модели'],
            ],
        ]);

        Yii::$app->session->set('products_filter', Yii::$app->request->queryString);

        $category = Category::findOne($category_id);
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $sizes = Size::find()->where(['category_id' => $category_id])->all();
        if (!count($sizes)) {
            $sizes = Size::find()->where('ISNULL(category_id)')->all();
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'pagination' => $dataProvider->getPagination(),
            'category' => $category,
            'sizes' => $sizes,
            'colors' => Color::find()->asArray()->all(),
            'rates' => Rate::find()->asArray()->all(),
            'marks' => Mark::find()->asArray()->all(),
            'amounts' => Amount::find()->asArray()->all(),
            'sort' => $sort,
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

        $sizes = Size::find()->where(['category_id' => $model->category_id])->all();
        if (!count($sizes)) {
            $sizes = Size::find()->where('ISNULL(category_id)')->all();
        }

        return $this->render('view', [
            'model' => $model,
            'category' => $model->category,
            'sizes' => $sizes,
            'dataProvider' => new ActiveDataProvider([
                //'query' => ReceiptItem::find()->where(['product_id' => $id])->groupBy(['order_id']),
                'query' => ReceiptItem::find()->where(['product_id' => $id]),
            ]),
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
        $model->position = 0;

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
            if (in_array($category->type, [2, 4, 6])) {
                $model->recommended_price = $model->purchase_price * $curr_rate;
            } else {
                $model->recommended_price_small = $model->purchase_price_small * $curr_rate;
                $model->recommended_price_big = $model->purchase_price_big * $curr_rate;
            }
            

            //Загрузка фото
            $model->photo_file = UploadedFile::getInstance($model, 'photo');
            if ($model->photo_file) {
                $image_path = '/files/'.time().'.'. $model->photo_file->extension;
                $model->photo_file->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo = $image_path;
            }

            //Загрузка фото2
            $model->photo_file2 = UploadedFile::getInstance($model, 'photo2');
            if ($model->photo_file2) {
                $image_path = '/files/'.time().'2.'. $model->photo_file2->extension;
                $model->photo_file2->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo2 = $image_path;
            }

            //Загрузка фото3
            $model->photo_file3 = UploadedFile::getInstance($model, 'photo3');
            if ($model->photo_file3) {
                $image_path = '/files/'.time().'3.'. $model->photo_file3->extension;
                $model->photo_file3->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo3 = $image_path;
            }

            if ($model->save()) {
                $product_name = '';
                if ($model->marka) $product_name .= $model->marka->name.' ';
                if ($model->model) $product_name .= $model->model->name.' ';
                if ($model->color) $product_name .= $model->color->name.' ';
                $model->name = trim($product_name);
                // Проверка на дубликат
                if ($model->model_id > 1000) {
                    $search_arr = ['category_id' => $model->category_id, 'marka_id' => $model->marka_id, 'model_id' => ($model->model_id - 1000)];
                    if ($model->color_id) $search_arr['color_id'] = $model->color_id;
                    $dublicat = Product::findOne($search_arr);
                    if ($dublicat and $dublicat->id != $model->id) {
                        $model->modifier = 1;
                    } else {
                        $model->modifier = 0;
                    }
                }
                $model->save();

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

                //Сохранение ценовых категорий
                if (isset($post['Product']['price_category'])) {
                    foreach ($post['Product']['price_category'] as $price_category) {
                        $new_price_category = new ProductPriceCategory();
                        $new_price_category->product_id = $model->id;
                        $new_price_category->price_category_id = $price_category;
                        $new_price_category->save();
                    }
                }

                //Сохранение фасонов
                if (isset($post['Product']['fashion'])) {
                    foreach ($post['Product']['fashion'] as $fashion) {
                        $new_fashion = new ProductFashion();
                        $new_fashion->product_id = $model->id;
                        $new_fashion->fashion_id = $fashion;
                        $new_fashion->save();
                    }
                }

                //Сохранение особенностей
                if (isset($post['Product']['feature'])) {
                    foreach ($post['Product']['feature'] as $feature) {
                        $new_feature = new ProductFeature();
                        $new_feature->product_id = $model->id;
                        $new_feature->feature_id = $feature;
                        $new_feature->save();
                    }
                }

                //Сохранение поводов
                if (isset($post['Product']['occasion'])) {
                    foreach ($post['Product']['occasion'] as $occasion) {
                        $new_occasion = new ProductOccasion();
                        $new_occasion->product_id = $model->id;
                        $new_occasion->occasion_id = $occasion;
                        $new_occasion->save();
                    }
                }

                return $this->redirect(['index', 'category_id' => $model->category->id]);
            }
        } else {
            $sizes = Size::find()->where(['category_id' => $category_id])->all();
            if (!count($sizes)) {
                $sizes = Size::find()->where('ISNULL(category_id)')->all();
            }

            return $this->render('create', [
                'model' => $model,
                'sizes' => $sizes,
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
            $new_filename = '/files/'.time().'.'. pathinfo($model->photo, PATHINFO_EXTENSION);
            if (copy(\Yii::$app->basePath.'/public_html'.$model->photo, \Yii::$app->basePath.'/public_html'.$new_filename)) {
                $clone->photo = $new_filename;
            }
        }

        if ($clone->save()) {
            // Проверка на дубликат
            if ($clone->model_id > 1000) {
                $search_arr = ['category_id' => $clone->category_id, 'marka_id' => $clone->marka_id, 'model_id' => ($clone->model_id - 1000)];
                if ($clone->color_id) $search_arr['color_id'] = $clone->color_id;
                $dublicat = Product::findOne($search_arr);
                if ($dublicat and $dublicat->id != $model->id) {
                    $clone->modifier = 1;
                } else {
                    $clone->modifier = 0;
                }
                $clone->save();
            }

            //Сохранение кол-ва
            foreach (Amount::find()->where(['product_id' => $id])->all() as $amount_obj) {
                $new_amount = new Amount();
                $new_amount->attributes = $amount_obj->attributes;
                $new_amount->product_id = $clone->id;
                $new_amount->save();
            }
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
        $old_photo2 = $model->photo2;
        $old_photo3 = $model->photo3;
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
            if (in_array($model->category->type, [2, 4, 6])) {
                $model->recommended_price = $model->purchase_price * $curr_rate;
            } else {
                $model->recommended_price_small = $model->purchase_price_small * $curr_rate;
                $model->recommended_price_big = $model->purchase_price_big * $curr_rate;
            }

            //Загрузка фото
            $model->photo_file = UploadedFile::getInstance($model, 'photo');
            if ($model->photo_file) {
                $image_path = '/files/'.time().'.'. $model->photo_file->extension;
                $model->photo_file->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo = $image_path;
                //Удаление старого фото
                if ($old_photo and file_exists(\Yii::$app->basePath.'/public_html'.$old_photo)) 
                    unlink(\Yii::$app->basePath.'/public_html'.$old_photo);
            } else {
                $model->photo = $old_photo;
            }

            //Загрузка фото2
            $model->photo_file2 = UploadedFile::getInstance($model, 'photo2');
            if ($model->photo_file2) {
                $image_path = '/files/'.time().'2.'. $model->photo_file2->extension;
                $model->photo_file2->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo2 = $image_path;
                //Удаление старого фото
                if ($old_photo2 and file_exists(\Yii::$app->basePath.'/public_html'.$old_photo2)) 
                    unlink(\Yii::$app->basePath.'/public_html'.$old_photo2);
            } else {
                $model->photo2 = $old_photo2;
            }

            //Загрузка фото3
            $model->photo_file3 = UploadedFile::getInstance($model, 'photo3');
            if ($model->photo_file3) {
                $image_path = '/files/'.time().'3.'. $model->photo_file3->extension;
                $model->photo_file3->saveAs(\Yii::$app->basePath.'/public_html'.$image_path);
                $model->photo3 = $image_path;
                //Удаление старого фото
                if ($old_photo3 and file_exists(\Yii::$app->basePath.'/public_html'.$old_photo3)) 
                    unlink(\Yii::$app->basePath.'/public_html'.$old_photo3);
            } else {
                $model->photo3 = $old_photo3;
            }

            if ($model->save()) {
                $product_name = '';
                if ($model->marka) $product_name .= $model->marka->name.' ';
                if ($model->model) $product_name .= $model->model->name.' ';
                if ($model->color) $product_name .= $model->color->name.' ';
                $model->name = trim($product_name);
                // Проверка на дубликат
                if ($model->model_id > 1000) {
                    $search_arr = ['category_id' => $model->category_id, 'marka_id' => $model->marka_id, 'model_id' => ($model->model_id - 1000)];
                    if ($model->color_id) $search_arr['color_id'] = $model->color_id;
                    $dublicat = Product::findOne($search_arr);
                    if ($dublicat and $dublicat->id != $model->id) {
                        $model->modifier = 1;
                    } else {
                        $model->modifier = 0;
                    }
                }
                $model->save();

                //Сохранение кол-ва
                Amount::deleteAll(['product_id' => $id]);
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

                //Сохранение ценовых категорий
                ProductPriceCategory::deleteAll(['product_id' => $id]);
                if (isset($post['Product']['price_category'])) {
                    foreach ($post['Product']['price_category'] as $price_category) {
                        $new_price_category = new ProductPriceCategory();
                        $new_price_category->product_id = $model->id;
                        $new_price_category->price_category_id = $price_category;
                        $new_price_category->save();
                    }
                }

                //Сохранение силуэтов
                ProductFashion::deleteAll(['product_id' => $id]);
                if (isset($post['Product']['fashion'])) {
                    foreach ($post['Product']['fashion'] as $fashion) {
                        $new_fashion = new ProductFashion();
                        $new_fashion->product_id = $model->id;
                        $new_fashion->fashion_id = $fashion;
                        $new_fashion->save();
                    }
                }

                //Сохранение особенностей
                ProductFeature::deleteAll(['product_id' => $id]);
                if (isset($post['Product']['feature'])) {
                    foreach ($post['Product']['feature'] as $feature) {
                        $new_feature = new ProductFeature();
                        $new_feature->product_id = $model->id;
                        $new_feature->feature_id = $feature;
                        $new_feature->save();
                    }
                }

                //Сохранение поводов
                ProductOccasion::deleteAll(['product_id' => $id]);
                if (isset($post['Product']['occasion'])) {
                    foreach ($post['Product']['occasion'] as $occasion) {
                        $new_occasion = new ProductOccasion();
                        $new_occasion->product_id = $model->id;
                        $new_occasion->occasion_id = $occasion;
                        $new_occasion->save();
                    }
                }

                if (Yii::$app->session->get('products_filter')) {
                    return $this->redirect(['index?'.Yii::$app->session->get('products_filter')]);
                } else {
                    return $this->redirect(['index', 'category_id' => $model->category->id]);
                }
            }
        } else {
            $sizes = Size::find()->where(['category_id' => $model->category_id])->all();
            if (!count($sizes)) {
                $sizes = Size::find()->where('ISNULL(category_id)')->all();
            }

            return $this->render('update', [
                'model' => $model,
                'sizes' => $sizes,
                'category' => $model->category,
            ]);
        }
    }

    public function actionBarcode($id)
    {
        $model = Product::findByBarcode($id);
        //if ($model) {
            return $this->renderAjax('barcode', [
                'barcode' => $id,
                'model' => $model
            ]);
        //} else {
        //    Yii::$app->session->setFlash('find_by_barcode_error', 'Товар не найден');
        //    return $this->redirect(Yii::$app->request->referrer);
        //}
    }

    public function actionPrintCodes($codes = '')
    {
        return $this->renderAjax('print_codes', [
            'codes' => explode('-', $codes),
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    // public function actionDelete($id)
    // {
    //     $model = $this->findModel($id);
    //     $old_photo = $model->photo;
    //     $model->delete();
    //     //Удаление кол-ва
    //     Amount::deleteAll(['product_id' => $id]);
    //     //Удаление старого фото
    //     if ($old_photo) unlink(\Yii::$app->basePath.'/public_html'.$old_photo);

    //     return $this->redirect(['index', 'category_id' => $model->category->id]);
    // }


    public function actionDeleteImage($id, $name = 'photo')
    {
        $model = $this->findModel($id);
        if ($model->$name and file_exists(Yii::$app->basePath.'/public_html'.$model->$name)) {
            unlink(Yii::$app->basePath.'/public_html'.$model->$name);
            $model->$name = NULL;
            $model->save(false);
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = true;
        $model->save(false);
        return $this->redirect(['index', 'category_id' => $model->category->id]);
    }

    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->is_deleted = false;
        $model->save(false);
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
    



    public function actionGenerateFid()
    {
        $str_main = '';
        $str_double = [];
        if($models = Product::find()->all()) {
            $str_main .= "id; title; description; availability; condition; price; link; image_link; brand\r\n";
            foreach($models as $model) {
                $str = '';
                if($model->amounts) {
                    foreach($model->amounts as $product_amount) {
                        $product_id = $this->getProductId($model, $product_amount);
                        $str .= $model->name.';';
                        $str .= $this->getProductDescription($model, $product_amount).';';
                        $str .= $this->getAvaliability($product_amount).';';
                        $str .= 'new;';
                        $str .= $model->price ? $model->price.' RUB;' : $model->price_small.' RUB;';
                        $str .= 'https://wedding-spb.ru/catalog/view/'.$model->id.';';
                        $str .= $this->getProductImageLink($model).';';
                        $str .= $this->getMarka($model).';';
                        $str .= "\r\n";
                        if(!in_array($str, $str_double)) {
                            if($str) {
                                $str_main .= $product_id.';'.$str;
                                $str_double[] = $str;
                            }
                        }
                        $str = '';
                    }
                } else {
                    $product_id = $model->id;
                    $str .= $model->name.';';
                    $str .= $this->getProductDescription($model).';';
                    $str .= ';';
                    $str .= 'new;';
                    $str .= $model->price ? $model->price.' RUB;' : $model->price_small.' RUB;';
                    $str .= 'https://wedding-spb.ru/catalog/view/'.$model->id.';';
                    $str .= $this->getProductImageLink($model).';';
                    $str .= $this->getMarka($model).';';
                    $str .= "\r\n";
                }
                if(!in_array($str, $str_double)) {
                    if($str) {
                        $str_main .= $product_id.';'.$str;
                        $str_double[] = $str;
                    }
                }
            }
            Yii::$app->response->sendContentAsFile($str_main, 'fid.csv');
        }
    }
    public function getMarka($model)
    {
        return $model->marka ? $model->marka->name : '';
    }
    public function getProductDescription($model, $amount = null)
    {
        $str = '';
        if($model->category) {
            $str .= ' '.$model->category->name;
        }
        if($amount && $amount->size) {
            $str .= ' размер - '.$amount->size->name;
        }
        if($model->model) {
            $str .= ' модель - '.$model->model->name;
        }
        if($model->color) {
            $str .= ' цвет - '.$model->color->name;
        }
        if($model->description) {
            $str .= ' '.$model->description;
        }

        return $str;
    }
    public function getAvaliability($amount)
    {
        $str = 'in stock';
        if($amount) {
            switch($amount->amount_type) {
                case Amount::TYPE_HALL : $str = 'in stock';
                    break;
                case Amount::TYPE_WAREHOUSE : $str = 'in stock';
                    break;
                case Amount::TYPE_WAIT : $str = 'available for order';
                    break;
            }
        }
        return $str;
    }
    public function getProductId($model, $amount = null)
    {
        return $model->id.'-'.$amount->id;
    }
    public function getProductImageLink($model)
    {
        if($model->photo) {
            return 'https://wedding-spb.ru'.$model->photo;
        }
        return '';
    }
}
