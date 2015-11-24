<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $marka
 * @property string $model
 * @property string $color
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
 * @property double $ratio
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    public $size;
    public $amount;

    public $marka_new;
    public $model_new;
    public $color_new;
    public $size_new;
    public $ratio_new;
    
    public $marka_or;
    public $model_or;
    public $color_or;
    public $size_or;
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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_id', 'purchase_price_small', 'purchase_price_big', 'recommended_price_small', 'recommended_price_big', 'price_small', 'price_big', 'ratio'], 'required'],
            [['purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'recommended_price_small', 'recommended_price_big', 'price_small', 'price_big', 'ratio'], 'number'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['marka', 'model', 'color', 'description', 'photo'], 'string', 'max' => 255]
        ];
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
            'marka' => 'Марка',
            'marka_new' => 'Новая марка',
            'model' => 'Модель',
            'model_new' => 'Новая модель',
            'color' => 'Цвет',
            'color_new' => 'Новый цвет',
            'size' => 'Размер',
            'size_new' => 'Новый размер',
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
