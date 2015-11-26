<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "amounts".
 *
 * @property integer $id
 * @property integer $product_id
 * @property integer $amount_type
 * @property integer $size_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Amount extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'amounts';
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
            [['product_id', 'amount_type', 'amount'], 'required'],
            [['product_id', 'amount_type', 'amount', 'size_id', 'created_at', 'updated_at'], 'integer']
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
            'product_id' => 'Товар',
            'amount_type' => 'Тип',
            'amount' => 'Количество',
            'size_id' => 'Размер',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
