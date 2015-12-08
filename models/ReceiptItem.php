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
            [['receipt_id', 'product_id', 'size_id', 'amount', 'sale', 'gift', 'created_at', 'updated_at'], 'integer'],
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

    public function getSize()
    {
        return $this->hasOne(Size::className(), ['id' => 'size_id']);
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
            'sale' => 'Скидка',
            'total_price' => 'Итого',
            'gift' => 'Подарок',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }
}
