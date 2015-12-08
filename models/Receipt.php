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
 * @property double $change
 * @property integer $manager_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Receipt extends \yii\db\ActiveRecord
{
    const PAY_CASH = 0;
    const PAY_NOCASH = 1;

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
            [['payment_type', 'total_amount', 'price', 'sale', 'total_price', 'manager_id'], 'required'],
            [['payment_type', 'total_amount', 'manager_id', 'created_at', 'updated_at'], 'integer'],
            [['price', 'sale', 'total_price', 'change'], 'number']
        ];
    }

    /**
    * Relations
    */
    public function getItems()
    {
        return $this->hasMany(ReceiptItem::className(), ['receipt_id' => 'id']);
    }

    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
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
            'change' => 'Сдача',
            'manager_id' => 'Менеджер',
            'created_at' => 'Дата добавления',
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
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getPayCashes() {
        return [
            self::PAY_CASH => 'наличными',
            self::PAY_NOCASH => 'картой',
        ];
    }

}
