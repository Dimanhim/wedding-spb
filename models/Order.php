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
 * @property integer $payment
 * @property double $total_payed
 * @property double $total_rest
 * @property integer $total_amount
 * @property double $total_price
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends \yii\db\ActiveRecord
{
    const STATUS_CANCELED   = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_PART_PAYED = 2;
    const STATUS_PAYED      = 3;
    const STATUS_FULL_COME  = 4;

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
            [['await_date', 'payment', 'total_amount', 'total_price', 'status'], 'required'],
            [['await_date', 'payment', 'total_amount', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'payment' => 'Способ оплаты',
            'total_payed' => 'Оплачено',
            'total_rest' => 'Остаток',
            'total_amount' => 'Количество',
            'total_price' => 'Сумма',
            'status' => 'Статус',
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
    public function getStatusLabel() {
        switch ($this->status) {
            case self::STATUS_CANCELED:
                return '<span class="label label-danger">отменен</span>';
                break;
            case self::STATUS_ACTIVE:
                return '<span class="label label-primary">сформирован</span>';
                break;
            case self::STATUS_PART_PAYED:
                return '<span class="label label-warning">частично оплачен</span>';
                break;
            case self::STATUS_PAYED:
                return '<span class="label label-success">оплачен</span>';
                break;
            case self::STATUS_FULL_COME:
                return '<span class="label label-default">полностью пришел</span>';
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
            self::STATUS_PART_PAYED => 'частично оплачен',
            self::STATUS_PAYED => 'оплачен',
            self::STATUS_FULL_COME => 'полностью пришел',
        ];
    }

    //Меняет наличие товаров при изменении статуса заказа
    public function updateAmountByOrderStatus($order, $status)
    {
        $amount_type = false;

        //Если полностью пришел то переводим на Склад
        if ($status == self::STATUS_FULL_COME) {
            $amount_type = 1;
        }

        //Если частично или полностью оплачен, то Ждём
        if ($status == self::STATUS_PART_PAYED or $status == self::STATUS_PAYED) {
            $amount_type = 2;
        }
        
        if ($amount_type) {
            foreach ($order->items as $order_item) {
                $query_arr = ['product_id' => $order_item->product_id, 'amount_type' => $amount_type];
                if ($order_item->size_id) $query_arr['size_id'] = $order_item->size_id;
                $amount = Amount::find()->where($query_arr)->one();
                if ($amount) {
                    $amount->amount += $order_item->amount;
                    $amount->save();
                } else{
                    //Если такого наличия нет, то создаём
                    $new_amount = new Amount();
                    $new_amount->product_id = $order_item->product_id;
                    if ($order_item->size_id) $new_amount->size_id = $order_item->size_id;
                    $new_amount->amount_type = $amount_type;
                    $new_amount->amount = $order_item->amount;
                    $new_amount->save();
                }

                if ($status == self::STATUS_FULL_COME) {
                    $order_item->status = OrderItem::STATUS_FULL_COME;
                    $order_item->save();
                }
            }
        } else {
            return false;
        }
        return true;
    }


}
