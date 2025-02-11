<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "receipts".
 *
 * @property integer $id
 * @property integer $payment_type
 * @property integer $total_amount
 * @property double $price
 * @property double $sale
 * @property double $total_price
 * @property double $nocash_total
 * @property double $cash_total
 * @property double $change
 * @property integer $manager_id
 * @property string $card_number
 * @property integer $created_at
 * @property integer $updated_at
 */
class Receipt extends \yii\db\ActiveRecord
{
    const PAY_CASH = 0;
    const PAY_NOCASH = 1;
    const PAY_COMBO = 2;

    public $purchase_price;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receipts';
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
            [['payment_type', 'total_amount', 'price', 'sale', 'total_price', 'manager_id', 'is_closed'], 'required'],
            [['payment_type', 'total_amount', 'manager_id', 'created_at', 'updated_at', 'is_closed'], 'integer'],
            [['price', 'sale', 'total_price', 'change', 'cash_total', 'nocash_total', 'purchase_price'], 'number'],
            [['card_number'], 'string', 'max' => 255]
        ];
    }

    /**
    * Relations
    */
    public function getItems()
    {
        return $this->hasMany(ReceiptItem::className(), ['receipt_id' => 'id']);
    }

    public static function getItemsExt()
    {
        return ReceiptItem::find()
            ->select('`receipt_items`.*, `products`.`purchase_price`, `products`.`purchase_price_small`, `products`.`purchase_price_big`')
            //->where(['receipt_items.receipt_id' => $this->id])
            ->leftJoin('products', 'products.id = receipt_items.product_id')->asArray()->all();
    }

    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
    }

    public function pageTotal($models, $fieldName) {
        $total = 0;
        foreach($models as $model){
            $total += $model[$fieldName];
        }
        return number_format($total, 0, ',', ' ');
    }

    public function purchaseTotal($models, $sizes, $itemsExt) {
        $purchase_price = 0;
        foreach ($models as $model) {
            $reciept_items = array_filter($itemsExt, function($item) use ($model) {
                return $item['receipt_id'] == $model->id;
            });
            foreach ($reciept_items as $reciept_item) {
                if ($reciept_item['size_id']) {
                    $size_key = array_search($reciept_item['size_id'], array_column($sizes, 'id'));
                    if ($sizes[$size_key]['name'] < 50 and $reciept_item['purchase_price_small']) {
                        $purchase_price += $reciept_item['purchase_price_small'];
                    }
                    if ($sizes[$size_key]['name'] >= 50 and $reciept_item['purchase_price_big']) {
                        $purchase_price += $reciept_item['purchase_price_big'];
                    }
                }
                $purchase_price += $reciept_item['purchase_price'];
            }
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
            'payment_type' => 'Оплата',
            'total_amount' => 'Кол-во',
            'price' => 'Сумма',
            'sale' => 'Скидка',
            'total_price' => 'Итого',
            'cash_total' => 'Наличными',
            'nocash_total' => 'Безналом',
            'purchase_price' => 'Закупка',
            'change' => 'Сдача',
            'manager_id' => 'Менеджер',
            'card_number' => 'Номер карты',
            'created_at' => 'Дата добавления',
            'created_at_begin' => 'Дата добавления',
            'created_at_end' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
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
            case self::PAY_COMBO:
                return 'наличными+картой';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getPayCashString() {
        switch ($this->payment_type) {
            case self::PAY_CASH:
                return 'наличными';
                break;
            case self::PAY_NOCASH:
                return 'картой';
                break;
            case self::PAY_COMBO:
                return 'наличными: '.$this->cash_total.' р. картой: '.$this->nocash_total.' р.';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getPayCashes() {
        return [
            self::PAY_CASH => 'наличными',
            self::PAY_NOCASH => 'картой',
            self::PAY_COMBO => 'наличными+картой',
        ];
    }

    public function recalc() {
        $amount = 0;
        $price = 0;
        $sale = 0;
        $total_price = 0;
        foreach ($this->items as $item) {
            $amount += $item->amount;
            $price += $item->price;
            $sale += $item->sale;
            $total_price += $item->total_price;
        }
        $this->total_amount = $amount;
        $this->price = $price;
        $this->sale = $sale;
        $this->total_price = $total_price;
        return $this->save();
    }

}
