<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "categories".
 *
 * @property integer $id
 * @property string $name
 * @property integer $type
 * @property string $image
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends \yii\db\ActiveRecord
{
    public $image_field;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'categories';
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
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['name', 'image'], 'string', 'max' => 255],
            ['image_field', 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'name' => 'Название',
            'type' => 'Тип',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
