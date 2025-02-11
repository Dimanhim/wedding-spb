<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "receipt_items".
 *
 * @property integer $id
 * @property integer $receipt_id
 * @property integer $product_id
 * @property integer $size_id
 * @property integer $amount
 * @property double $price
 * @property integer $sale
 * @property double $total_price
 * @property integer $gift
 * @property integer $created_at
 * @property integer $updated_at
 */
class ReceiptItem extends \yii\db\ActiveRecord
{
    public $manager_id;
    public $marka_id;
    public $category_id;
    public $product_type;
    public $color_name;
    public $marka_name;
    public $model_name;
    public $purchase_price;
    public $purchase_price_small;
    public $purchase_price_big;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipt_items';
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
            [['receipt_id', 'product_id', 'amount', 'price', 'total_price'], 'required'],
            [['receipt_id', 'product_id', 'size_id', 'amount', 'sale', 'gift', 'created_at', 'updated_at', 'manager_id', 'marka_id', 'category_id'], 'integer'],
            [['price', 'total_price'], 'number']
        ];
    }

    /**
    * Relations
    */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'receipt_id']);
    }

    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
    }

    public function pageTotal($models, $fieldName) {
        $total = 0;
        foreach($models as $model){
            $total += $model[$fieldName];
        }
        return number_format($total, 0, ',', ' ');
    }

    public function purchaseTotal($models, $sizes) {
        $purchase_price = 0;
        foreach ($models as $model) {
            if ($model->size_id) {
                $size_key = array_search($model->size_id, array_column($sizes, 'id'));
                if ($sizes[$size_key]['name'] < 50 and $model->purchase_price_small) {
                    $purchase_price += $model->purchase_price_small;
                }
                if ($sizes[$size_key]['name'] >= 50 and $model->purchase_price_big) {
                    $purchase_price += $model->purchase_price_big;
                }
            }
            $purchase_price += $model->purchase_price;
        }
        return number_format($purchase_price, 0, ',', ' ');
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'receipt_id' => 'Чек',
            'product_id' => 'Товар',
            'size_id' => 'Размер',
            'amount' => 'Кол-во',
            'price' => 'Цена',
            'purchase_price' => 'Закупка',
            'sale' => 'Скидка',
            'total_price' => 'Итого',
            'gift' => 'Подарок',
            'manager_id' => 'Менеджер',
            'marka_id' => 'Марка',
            'category_id' => 'Категория',
            'product_type' => 'Тип товара',
            'created_at_begin' => 'Дата добавления',
            'created_at_end' => 'Дата добавления',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }
}
