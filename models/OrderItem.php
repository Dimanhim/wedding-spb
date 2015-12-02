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
 * @property integer $status
 * @property integer $arrived
 * @property integer $created_at
 * @property integer $updated_at
 */
class OrderItem extends \yii\db\ActiveRecord
{
    const STATUS_CANCELED   = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_PART_COME  = 2;
    const STATUS_FULL_COME  = 3;

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
            [['order_id', 'product_id', 'amount', 'price', 'status'], 'required'],
            [['order_id', 'product_id', 'size_id', 'amount', 'status', 'arrived', 'created_at', 'updated_at'], 'integer'],
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
    public function getStatusLabel() {
        switch ($this->status) {
            case self::STATUS_CANCELED:
                return '<span class="label label-danger">отменен</span>';
                break;
            case self::STATUS_ACTIVE:
                return '<span class="label label-primary">сформирован</span>';
                break;
            case self::STATUS_PART_COME:
                return '<span class="label label-warning">частично пришел</span>';
                break;
            case self::STATUS_FULL_COME:
                return '<span class="label label-success">оплачен</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getStatuses() {
        return [
            self::STATUS_CANCELED => 'отменен',
            self::STATUS_ACTIVE => 'сформирован',
            self::STATUS_PART_COME => 'частично пришел',
            self::STATUS_FULL_COME => 'полностью пришел',
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
            'status' => 'Статус',
            'arrived' => 'Пришло',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }
}
