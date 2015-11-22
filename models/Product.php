<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "products".
 *
 * @property integer $id
 * @property string $marka
 * @property string $artikul
 * @property string $color
 * @property string $description
 * @property string $photo
 * @property double $purchase_price_small
 * @property double $purchase_price_big
 * @property double $purchase_price_small_dol
 * @property double $purchase_price_big_dol
 * @property double $recommended_price_small
 * @property double $recommended_price_big
 * @property double $price_small
 * @property double $price_big
 * @property double $price_ratio
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'products';
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
            [['purchase_price_small', 'purchase_price_big', 'recommended_price_small', 'recommended_price_big', 'price_small', 'price_big', 'price_ratio', 'created_at', 'updated_at'], 'required'],
            [['purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'recommended_price_small', 'recommended_price_big', 'price_small', 'price_big', 'price_ratio'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
            [['marka', 'artikul', 'color', 'description', 'photo'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'marka' => 'Марка',
            'artikul' => 'Артикул',
            'color' => 'Цвет',
            'description' => 'Описание',
            'photo' => 'Изображение',
            'purchase_price_small' => 'Закупка (<48)',
            'purchase_price_big' => 'Закупка (>50)',
            'purchase_price_small_dol' => 'Закупка (<48), $',
            'purchase_price_big_dol' => 'Закупка (>50), $',
            'recommended_price_small' => 'Рекомендованная цена (<48)',
            'recommended_price_big' => 'Рекомендованная цена (>50)',
            'price_small' => 'Цена (<48)',
            'price_big' => 'Цена (>50)',
            'price_ratio' => 'Коэффициент',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
