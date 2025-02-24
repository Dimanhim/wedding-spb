<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "primerka_products".
 *
 * @property integer $id
 * @property integer $primerka_id
 * @property integer $product_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PrimerkaProduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'primerka_products';
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
            [['primerka_id', 'product_id'], 'required'],
            [['primerka_id', 'product_id'], 'integer'],
        ];
    }

    public function getPrimerka()
    {
        return $this->hasOne(Primerka::className(), ['id' => 'primerka_id']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['id' => 'product_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'primerka_id' => 'Примерка',
            'product_id' => 'Товар',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
