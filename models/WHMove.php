<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "w_h_moves".
 *
 * @property integer $id
 * @property integer $total_amount
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class WHMove extends \yii\db\ActiveRecord
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
        return 'w_h_moves';
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
            [['total_amount', 'status'], 'required'],
            [['total_amount', 'status', 'created_at', 'updated_at'], 'integer']
        ];
    }

    /**
    * Relations
    */
    public function getItems()
    {
        return $this->hasMany(WHMovesItem::className(), ['move_id' => 'id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'total_amount' => 'Количество',
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
                return '<span class="label label-primary">еще на складе</span>';
                break;
            case self::STATUS_PART_COME:
                return '<span class="label label-warning">частично пришел</span>';
                break;
            case self::STATUS_DONE:
                return '<span class="label label-success">уже в зале</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getStatuses() {
        return [
            self::STATUS_CANCELED => 'отменен',
            self::STATUS_ACTIVE => 'еще на складе',
            self::STATUS_PART_COME => 'частично пришел',
            self::STATUS_DONE => 'уже в зале',
        ];
    }
}
