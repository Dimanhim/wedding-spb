<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "h_w_moves_items".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $move_id
 * @property integer $size_id
 * @property integer $amount
 * @property integer $status
 * @property integer $arrived
 * @property integer $created_at
 * @property integer $updated_at
 */
class HWMovesItem extends \yii\db\ActiveRecord
{
    const STATUS_CANCELED   = 0;
    const STATUS_ACTIVE     = 1;
    const STATUS_PART_COME  = 2;
    const STATUS_DONE       = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'h_w_moves_items';
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
            [['move_id', 'product_id', 'amount', 'status'], 'required'],
            [['move_id', 'product_id', 'size_id', 'amount', 'status', 'arrived', 'created_at', 'updated_at'], 'integer']
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
            'move_id' => 'Перемещение',
            'product_id' => 'Товар',
            'size_id' => 'Размер',
            'amount' => 'Количество',
            'status' => 'Статус',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
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
                return '<span class="label label-primary">еще в зале</span>';
                break;
            case self::STATUS_PART_COME:
                return '<span class="label label-warning">частично пришел</span>';
                break;
            case self::STATUS_DONE:
                return '<span class="label label-success">уже на складе</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getStatuses() {
        return [
            self::STATUS_CANCELED => 'отменен',
            self::STATUS_ACTIVE => 'еще в зале',
            self::STATUS_PART_COME => 'частично пришел',
            self::STATUS_DONE => 'уже на складе',
        ];
    }
}
