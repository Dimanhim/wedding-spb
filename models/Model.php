<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "models".
 *
 * @property integer $id
 * @property integer $category_id
 * @property string $name
 * @property integer $created_at
 * @property integer $updated_at
 */
class Model extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'models';
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
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'category_id' => 'Категория',
            'name' => 'Название',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }
}
