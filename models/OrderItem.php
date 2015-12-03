<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "order_items".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $order_id
 * @property integer $size_id
 * @property integer $amount
 * @property double $price
 * @property integer $delivery_status
 * @property integer $arrived
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderItem extends \yii\db\ActiveRecord
{
    const DELIVERY_INIT  = 1;
    const DELIVERY_PART  = 2;
    const DELIVERY_FULL  = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'order_items';
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
            [['order_id', 'product_id', 'amount', 'price', 'delivery_status'], 'required'],
            [['order_id', 'product_id', 'size_id', 'amount', 'delivery_status', 'arrived', 'created_at', 'updated_at'], 'integer'],
            [['price'], 'number']
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
    * Statuses
    */
    public function getDeliveryStatusLabel() {
        switch ($this->delivery_status) {
            case self::DELIVERY_INIT:
                return '<span class="label label-primary">инициализирована</span>';
                break;
            case self::DELIVERY_PART:
                return '<span class="label label-warning">частично поступил</span>';
                break;
            case self::DELIVERY_FULL:
                return '<span class="label label-success">полностью поступил</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getDeliveryStatuses() {
        return [
            self::DELIVERY_INIT => 'инициализирована',
            self::DELIVERY_PART => 'частично поступил',
            self::DELIVERY_FULL => 'полностью поступил',
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'order_id' => 'Заказ', 
            'product_id' => 'Товар',
            'size_id' => 'Размер',
            'amount' => 'Количество',
            'price' => 'Сумма',
            'delivery_status' => 'Статус поставки',
            'arrived' => 'Пришло',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }
}
