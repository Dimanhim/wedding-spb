<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $marka_id
 * @property string $model_id
 * @property string $color_id
 * @property string $description
 * @property string $photo
 * @property double $purchase_price_small
 * @property double $purchase_price_big
 * @property double $purchase_price_small_dol
 * @property double $purchase_price_big_dol
 * @property double $recommended_price_small
 * @property double $recommended_price_big
 * @property double $price_small
 * @property double $price_big
 * @property double $ratio_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    public $sizes;
    public $amount;

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
        return [
            'type_1' => ['category_id', 'sizes', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
            'type_2' => ['category_id', 'marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
            'type_3' => ['category_id', 'sizes', 'marka', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
            'type_4' => ['category_id', 'marka_id', 'marka_new', 'model_id', 'model_new', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
            'type_5' => ['category_id', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
            'type_6' => ['category_id', 'marka_id', 'marka_new', 'description', 'photo', 'purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'purchase_price_small', 'purchase_price_big', 'price_small', 'price_big', 'sizes'], 'required'],
            [['purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'price_small', 'price_big', 'ratio_id', 'ratio_new'], 'number'],
            [['category_id'], 'integer'],
            [['marka_id', 'marka_new', 'model_id', 'model_new', 'color_id', 'color_new', 'description', 'photo'], 'string', 'max' => 255],
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

    public function getAmount()
    {
        return $this->hasMany(Amount::className(), ['product_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
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
            'photo' => 'Изображение',
            'purchase_price_small' => 'Закупка (<48)',
            'purchase_price_big' => 'Закупка (>50)',
            'purchase_price_small_dol' => 'Закупка (<48), $',
            'purchase_price_big_dol' => 'Закупка (>50), $',
            'recommended_price_small' => 'Рекомендованная цена (<48)',
            'recommended_price_big' => 'Рекомендованная цена (>50)',
            'price_small' => 'Цена (<48)',
            'price_big' => 'Цена (>50)',
            'ratio' => 'Коэффициент',
            'ratio_new' => 'Новый коэффициент',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
