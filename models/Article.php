<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "articles".
 *
 * @property integer $id
 * @property string $name
 * @property integer $category_id
 * @property string $introtext
 * @property string $image
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 */
class Article extends \yii\db\ActiveRecord
{
    public $image_field;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
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
            [['name'], 'required'],
            [['category_id', 'created_at', 'updated_at'], 'integer'],
            [['content', 'introtext'], 'string'],
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
            'category_id' => 'Категория',
            'introtext' => 'Описание',
            'image' => 'Изображение',
            'image_field' => 'Изображение',
            'content' => 'Контент',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
    * Relations
    */
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'category_id']);
    }
}
