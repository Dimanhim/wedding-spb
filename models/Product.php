<?php



namespace app\models;



use Yii;

use yii\behaviors\TimestampBehavior;



/**

 * This is the model class for table "products".

 *

 * @property integer $id

 * @property string $name

 * @property integer $category_id

 * @property string $marka_id

 * @property string $model_id

 * @property string $color_id

 * @property string $description

 * @property string $photo

 * @property string $photo2

 * @property string $photo3

 * @property double $purchase_price

 * @property double $purchase_price_small

 * @property double $purchase_price_big

 * @property double $purchase_price_dol

 * @property double $purchase_price_small_dol

 * @property double $purchase_price_big_dol

 * @property double $recommended_price

 * @property double $recommended_price_small

 * @property double $recommended_price_big

 * @property double $price

 * @property double $price_small

 * @property double $price_big

 * @property double $old_price

 * @property double $old_price_small

 * @property double $old_price_big

 * @property double $ratio_id

 * @property integer $purchase_date

 * @property integer $sell_date

 * @property integer $position

 * @property integer $new

 * @property integer $hide_on_web

 * @property integer $hide_on_tablet

 * @property integer $is_deleted

 * @property integer $total_show

 * @property integer $total_like

 * @property integer $modifier

 * @property integer $sale

 * @property integer $created_at

 * @property integer $updated_at

 */

class Product extends \yii\db\ActiveRecord

{

    public $photo_file;

    public $photo_file2;

    public $photo_file3;

    public $sizes;

    public $amount;

    public $price_category;

    public $fashion;

    public $feature;

    public $occasion;



    public $marka_new;

    public $model_new;

    public $color_new;

    public $ratio_new;



    public $marka_or;

    public $model_or;

    public $color_or;

    public $ratio_or;



    /**

     * @inheritdoc

     */

    public static function tableName()

    {

        return 'products';

    }



    public function behaviors()

    {

        return [

            TimestampBehavior::className()

        ];

    }



    public function scenarios()

    {

        $scenarios = parent::scenarios();

        $scenarios['type_1'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'sizes', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'old_price_small', 'old_price_big', 'ratio_id', 'ratio_new', 'recommended_price_small', 'recommended_price_big', 'sale'];

        $scenarios['type_2'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price', 'purchase_price_dol', 'price', 'old_price', 'ratio_id', 'ratio_new', 'recommended_price'];

        $scenarios['type_3'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'sizes', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'old_price_small', 'old_price_big', 'ratio_id', 'ratio_new', 'recommended_price_small', 'recommended_price_big'];

        $scenarios['type_4'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'marka_id', 'marka_new', 'model_id', 'model_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price', 'purchase_price_dol', 'price', 'old_price', 'ratio_id', 'ratio_new', 'recommended_price'];

        $scenarios['type_5'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'marka_id', 'marka_new', 'model_id', 'model_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'old_price_small', 'old_price_big', 'ratio_id', 'ratio_new', 'recommended_price_small', 'recommended_price_big'];

        $scenarios['type_6'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'marka_id', 'marka_new', 'model_id', 'model_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price', 'purchase_price_dol', 'price', 'old_price', 'ratio_id', 'ratio_new', 'recommended_price'];

        $scenarios['type_7'] = ['position', 'fashion', 'feature', 'occasion', 'total_show', 'total_like', 'modifier', 'price_category', 'name', 'amount', 'category_id', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'photo_file', 'photo_file2', 'photo_file3', 'sizes', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'instagram_description', 'photo', 'photo2', 'photo3', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'old_price_small', 'old_price_big', 'ratio_id', 'ratio_new', 'recommended_price_small', 'recommended_price_big'];

        return $scenarios;

    }



    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            [['category_id', 'purchase_price', 'purchase_price_small', 'price', 'price_small'], 'required'],

            [['purchase_price', 'purchase_price_small', 'purchase_price_big', 'purchase_price_dol', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price', 'price_small', 'price_big', 'old_price', 'old_price_small', 'old_price_big', 'ratio_id', 'ratio_new'], 'number'],

            [['position', 'category_id', 'marka_id', 'color_id', 'model_id', 'purchase_date', 'sell_date', 'created_at', 'updated_at', 'total_show', 'total_like', 'modifier', 'sale'], 'integer'],

            [['recommended_price', 'recommended_price_small', 'recommended_price_big', 'sizes', 'photo_file', 'photo_file2', 'photo_file3', 'purchase_price_big', 'price_big', 'hide_on_web', 'hide_on_tablet', 'new', 'is_deleted', 'amount', 'price_category', 'fashion', 'feature', 'occasion'], 'safe'],

            [['marka_new', 'model_new', 'color_new', 'description', 'photo', 'name', 'instagram_description'], 'string', 'max' => 255],

            ['marka_id', 'required', 'when' => function($model) {

                return empty($model->marka_new);

            }, 'whenClient' => "function (attribute, value) {

                return $('#product-marka_new').val() == '';

            }"],

            ['model_id', 'required', 'when' => function($model) {

                return empty($model->model_new);

            }, 'whenClient' => "function (attribute, value) {

                return $('#product-model_new').val() == '';

            }"],

            ['color_id', 'required', 'when' => function($model) {

                return empty($model->color_new);

            }, 'whenClient' => "function (attribute, value) {

                return $('#product-color_new').val() == '';

            }"],

            ['ratio_id', 'required', 'when' => function($model) {

                return empty($model->ratio_new);

            }, 'whenClient' => "function (attribute, value) {

                return $('#product-ratio_new').val() == '';

            }"],

        ];

    }



    /**

    * Relations

    */

    public function getCategory()

    {

        return $this->hasOne(Category::className(), ['id' => 'category_id']);

    }



    public function getMarka()

    {

        return $this->hasOne(Mark::className(), ['id' => 'marka_id']);

    }



    public function getModel()

    {

        return $this->hasOne(Model::className(), ['id' => 'model_id']);

    }



    public function getColor()

    {

        return $this->hasOne(Color::className(), ['id' => 'color_id']);

    }



    public function getRatio()

    {

        return $this->hasOne(Rate::className(), ['id' => 'ratio_id']);

    }



    // public function getAmountsArr()

    // {

    //     return $this->hasMany(Amount::className(), ['product_id' => 'id'])->asArray();

    // }



    public function getProductPriceCategories()

    {

        return $this->hasMany(ProductPriceCategory::className(), ['product_id' => 'id']);

    }



    public function getProductFashions()

    {

        return $this->hasMany(ProductFashion::className(), ['product_id' => 'id']);

    }



    public function getProductFeatures()

    {

        return $this->hasMany(ProductFeature::className(), ['product_id' => 'id']);

    }



    public function getProductOccasions()

    {

        return $this->hasMany(ProductOccasion::className(), ['product_id' => 'id']);

    }



    public function getAmounts()

    {

        return $this->hasMany(Amount::className(), ['product_id' => 'id']);

    }



    public function getBarcode($product, $size = 0)

    {

        $category = str_pad($product->category_id, 2, '0', STR_PAD_LEFT);

        if ($product->modifier == 1) $category += 50;

        $marka = str_pad($product->marka_id, 3, '0', STR_PAD_LEFT);

        $model = str_pad($product->model_id, 3, '0', STR_PAD_LEFT);

        if ((int) $model >= 1000 and (int) $model < 2000) $model = str_pad((int) $model - 1000, 3, '0', STR_PAD_LEFT);

        if ((int) $model >= 2000 and (int) $model < 3000) $model = str_pad((int) $model - 2000, 3, '0', STR_PAD_LEFT);

        if ((int) $model >= 3000 and (int) $model < 4000) $model = str_pad((int) $model - 3000, 3, '0', STR_PAD_LEFT);

        $color = str_pad($product->color_id, 2, '0', STR_PAD_LEFT);

        $size = str_pad($size, 2, '0', STR_PAD_LEFT);

        return $category.$marka.$model.$color.$size.'0';

    }



    public function findByBarcode($code)

    {

        $category = (int) ltrim(substr($code, 0, 2), '0');

        $marka = ltrim(substr($code, 2, 3), '0');

        $model = ltrim(substr($code, 5, 3), '0');

        $color = ltrim(substr($code, 8, 2), '0');

        //$size = ltrim(substr($code, 10, 2), '0');

        $search_arr = ['category_id' => $category, 'marka_id' => $marka];

        $search_arr['model_id'] = $model;

        if ($color) $search_arr['color_id'] = (int) $color;

        if ($category > 50) {

            $search_arr['category_id'] = (int) $category - 50;

            $search_arr['model_id'] = (int) $model + 1000;

        }

        Yii::info($category);

        Yii::info($marka);

        Yii::info($model);

        Yii::info($color);

        $product = Product::findOne($search_arr);

        if ($product) {

            return $product;

        } else {

            $search_arr['model_id'] = (int) $model + 1000;

            $product = Product::findOne($search_arr);

            if ($product) {

                return $product;

            } else {

                $search_arr['model_id'] = (int) $model + 2000;

                $product = Product::findOne($search_arr);

                if ($product) {

                    return $product;

                } else {

                    $search_arr['model_id'] = (int) $model + 3000;

                    $product = Product::findOne($search_arr);

                    if ($product) {

                        return $product;

                    } else {

                        if ($color) {

                            $search_arr['color_id'] = (int) ltrim(substr($code, 8, 3), '0');

                            $size_number = (int) ltrim(substr($code, 8, 1), '0');

                            $search_arr['model_id'] = (int) $model;

                            $product = Product::findOne($search_arr);

                            if ($product) {

                                return $product;

                            }

                            else {

                                $search_arr['model_id'] = (int) $model + 1000;

                                $product = Product::findOne($search_arr);

                                if ($product) {

                                    return $product;

                                } else {

                                    $search_arr['model_id'] = (int) $model + 2000;

                                    $product = Product::findOne($search_arr);

                                    if ($product) {

                                        return $product;

                                    } else {

                                        $search_arr['model_id'] = (int) $model + 3000;

                                        $product = Product::findOne($search_arr);

                                        if ($product) return $product;
                                        else {
                                            $search_arr['model_id'] = (string) $model.$size_number;
                                            $product = Product::findOne($search_arr);
                                            if($product) {
                                                return $product;
                                            }
                                            else {
                                                unset($search_arr['color_id']);
                                                $product = Product::findOne($search_arr);
                                                if($product) {
                                                    return $product;
                                                }
                                            }
                                        }

                                    }

                                }

                            }

                        }

                    }

                }

            }

        }

        return $product;

    }



    public function getActualPrice($size_obj = null)

    {

        if ($size_obj) {

            return ((int) $size_obj->name <= 48) ? $this->price_small : $this->price_big;

        }

        if ($this->price) return $this->price;

        if ($this->price_small) return $this->price_small;

        return 0;

    }



    /**

     * @inheritdoc

     */

    public function attributeLabels()

    {

        return [

            'id' => 'Id',

            'name' => 'Название',

            'amount' => 'Фактическое наличие товара',

            'category_id' => 'Категория',

            'marka_id' => 'Марка',

            'marka_new' => 'Новая марка',

            'model_id' => 'Модель',

            'model_new' => 'Новая модель',

            'color_id' => 'Цвет',

            'color_new' => 'Новый цвет',

            'sizes' => 'Размер',

            'description' => 'Описание',

            'instagram_description' => 'Описание для инстаграм',

            'photo' => 'Изображение',

            'photo2' => 'Изображение2',

            'photo3' => 'Изображение3',

            'photo_file' => 'Изображение',

            'photo_file2' => 'Изображение2',

            'photo_file3' => 'Изображение3',

            'purchase_price' => 'Закупка',

            'purchase_price_small' => 'Закупка (<48)',

            'purchase_price_big' => 'Закупка (>50)',

            'purchase_price_dol' => 'Закупка, $',

            'purchase_price_small_dol' => 'Закупка (<48), $',

            'purchase_price_big_dol' => 'Закупка (>50), $',

            'recommended_price' => 'Рекомендованная цена',

            'recommended_price_small' => 'Рекомендованная цена (<48)',

            'recommended_price_big' => 'Рекомендованная цена (>50)',

            'price' => 'Цена',

            'price_small' => 'Цена (<48)',

            'price_big' => 'Цена (>50)',

            'old_price' => 'Старая цена',

            'old_price_small' => 'Старая цена (<48)',

            'old_price_big' => 'Старая цена (>50)',

            'ratio_id' => 'Коэффициент',

            'ratio_new' => 'Новый коэффициент',

            'purchase_date' => 'Дата покупки',

            'sell_date' => 'Дата продажи',

            'hide_on_web' => 'Скрыть на сайте',

            'hide_on_tablet' => 'Скрыть на планшете',

            'new' => 'Новинка',

            'is_deleted' => 'В архиве',

            'price_category' => 'Ценовые категории',

            'fashion' => 'Фасон',

            'feature' => 'Особенность',

            'occasion' => 'Повод',

            'position' => 'Позиция',

            'total_show' => 'Просмотров',

            'total_like' => 'Лайков',

            'modifier' => 'Модификатор',

            'sale' => 'Распродажа',

            'created_at' => 'Дата добавления',

            'updated_at' => 'Дата изменения',

        ];

    }

}

