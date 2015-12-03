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
    const MOVE_INIT  = 1;
    const MOVE_PART  = 2;
    const MOVE_FULL  = 3;
    
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
            case self::MOVE_INIT:
                return '<span class="label label-primary">еще на складе</span>';
                break;
            case self::MOVE_PART:
                return '<span class="label label-warning">частично пришел</span>';
                break;
            case self::MOVE_FULL:
                return '<span class="label label-success">уже в зале</span>';
                break;
            default:
                return '<span class="label label-default">неизвестен</span>';
                break;
        }
    }

    public function getStatuses() {
        return [
            self::MOVE_INIT => 'еще на складе',
            self::MOVE_PART => 'частично пришел',
            self::MOVE_FULL => 'уже в зале',
        ];
    }
}
