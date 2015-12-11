<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\Amount;
use app\models\OrderItem;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property integer $await_date
 * @property integer $payment_type
 * @property double $total_payed
 * @property double $total_rest
 * @property integer $total_amount
 * @property double $total_price
 * @property integer $payment_status
 * @property integer $delivery_status
 * @property integer $accepted
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends \yii\db\ActiveRecord
{
    const PAY_CASH = 1;
    const PAY_NOCASH = 2;

    const PAYMENT_INIT = 1;
    const PAYMENT_PART = 2;
    const PAYMENT_FULL = 3;

    const DELIVERY_INIT = 1;
    const DELIVERY_PART = 2;
    const DELIVERY_FULL = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
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
            [['await_date', 'payment_type', 'total_amount', 'total_price', 'payment_status', 'delivery_status'], 'required'],
            [['await_date', 'payment_type', 'total_amount', 'payment_status', 'delivery_status', 'created_at', 'updated_at', 'accepted'], 'integer'],
            [['total_payed', 'total_rest', 'total_price'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'await_date' => 'Дата ожидания',
            'payment_type' => 'Оплата',
            'total_payed' => 'Оплачено',
            'total_rest' => 'Остаток',
            'total_amount' => 'Кол-во',
            'total_price' => 'Сумма',
            'payment_status' => 'Статус оплаты',
            'delivery_status' => 'Статус поставки',
            'accepted' => 'Принят',
            'created_at' => 'Дата заказа',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
    * Relations
    */
    public function getItems()
    {
        return $this->hasMany(OrderItem::className(), ['order_id' => 'id']);
    }

    /**
    * Statuses
    */
    public function getPayCashLabel() {
        switch ($this->payment_type) {
            case self::PAY_CASH:
                return 'наличными';
                break;
            case self::PAY_NOCASH:
                return 'картой';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getPaymentStatusLabel() {
        switch ($this->payment_status) {
            case self::PAYMENT_INIT:
                return '<span class="label label-primary">инициализирована</span>';
                break;
            case self::PAYMENT_PART:
                return '<span class="label label-warning">частично оплачен</span>';
                break;
            case self::PAYMENT_FULL:
                return '<span class="label label-success">полностью оплачен</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getPaymentStatuses() {
        return [
            self::PAYMENT_INIT => 'инициализирована',
            self::PAYMENT_PART => 'частично оплачен',
            self::PAYMENT_FULL => 'полностью оплачен',
        ];
    }

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

    public function acceptOrder() {
        if (!$this->accepted) {
            //Меняем наличие товара
            foreach ($this->items as $order_item) {
                $amount_query = ['product_id' => $order_item->product_id, 'amount_type' => Amount::TYPE_WAIT];
                if ($order_item->size) $amount_query['size_id'] = $order_item->size->id;
                if (($amount = Amount::find()->where($amount_query)->one()) !== null) {
                    $amount->amount += $order_item->amount;
                    $amount->save();
                } else {
                    $new_amount = new Amount();
                    $new_amount->product_id = $order_item->product_id;
                    if ($order_item->size) $new_amount->size_id = $order_item->size->id;
                    $new_amount->amount_type = Amount::TYPE_WAIT;
                    $new_amount->amount = $order_item->amount;
                    $new_amount->save();
                }
            }

            $this->accepted = 1;
        }
    }

    public function acceptOrderItem($order_item, $amount_type, $amount_val, $is_add) {
        $amount_query = ['product_id' => $order_item->product_id, 'amount_type' => $amount_type];
        if ($order_item->size) $amount_query['size_id'] = $order_item->size->id;
        if (($amount = Amount::find()->where($amount_query)->one()) !== null) {
            if ($is_add) {
                $amount->amount += $amount_val;
            } else {
                $amount->amount -= $amount_val;
            }
            $amount->save();
        } else {
            $new_amount = new Amount();
            $new_amount->product_id = $order_item->product_id;
            if ($order_item->size) $new_amount->size_id = $order_item->size->id;
            $new_amount->amount_type = $amount_type;
            $new_amount->amount = $amount_val;
            $new_amount->save();
        }
    }

}