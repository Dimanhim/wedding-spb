<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "static_pages".
 *
 * @property integer $id
 * @property string $name
 * @property string $filter_name
 * @property string $alias
 * @property string $h1
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $content
 * @property integer $type
 * @property integer $category_id
 * @property integer $parent_id
 * @property integer $fashion_id
 * @property integer $feature_id
 * @property integer $occasion_id
 * @property integer $color_id
 * @property integer $price_cat_id
 * @property integer $min_price
 * @property integer $max_price
 * @property integer $show_in_slider
 * @property integer $slider_image
 * @property integer $is_deleted
 * @property integer $created_at
 * @property integer $updated_at
 */
class StaticPage extends \yii\db\ActiveRecord
{
    public $slider_image_field;

    const TYPE_FASON     = 1;
    const TYPE_FEATURES  = 2;
    const TYPE_COLOR     = 3;
    const TYPE_PRICE     = 4;
    const TYPE_OCCASION  = 5;
    const TYPE_CAT       = 6;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'static_pages';
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
            [['name', 'alias', 'parent_id', 'type'], 'required'],
            [['content'], 'string'],
            [['parent_id', 'fashion_id', 'feature_id', 'occasion_id', 'color_id', 'price_cat_id', 'min_price', 'max_price', 'is_deleted', 'type', 'category_id', 'show_in_slider'], 'integer'],
            [['name', 'filter_name', 'alias', 'h1', 'title', 'keywords', 'description', 'slider_image'], 'string', 'max' => 255],
            ['slider_image_field', 'file'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Название',
            'filter_name' => 'Название для фильтра',
            'alias' => 'Адрес',
            'h1' => 'Мета-H1',
            'title' => 'Мета-Title',
            'keywords' => 'Мета-Keywords',
            'description' => 'Мета-Description',
            'content' => 'Контент',
            'type' => 'Тип',
            'category_id' => 'Категория',
            'parent_id' => 'Родительский раздел',
            'fashion_id' => 'Фасон',
            'feature_id' => 'Особенность',
            'occasion_id' => 'Повод',
            'color_id' => 'Цвет',
            'price_cat_id' => 'Ценовая категория',
            'min_price' => 'Минимальная цена',
            'max_price' => 'Максимальная цена',
            'show_in_slider' => 'Выводить в слайдере',
            'slider_image_field' => 'Картинка для слайдера',
            'slider_image' => 'Картинка для слайдера',
            'is_deleted' => 'Скрыта',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
    * Relations
    */
    public function getParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parent_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getFashion()
    {
        return $this->hasOne(Fashion::className(), ['id' => 'fashion_id']);
    }

    public function getFeature()
    {
        return $this->hasOne(Feature::className(), ['id' => 'feature_id']);
    }

    public function getColor()
    {
        return $this->hasOne(Color::className(), ['id' => 'color_id']);
    }

    public function getOccasion()
    {
        return $this->hasOne(Occasion::className(), ['id' => 'occasion_id']);
    }

    public function getPriceCategory()
    {
        return $this->hasOne(PriceCategory::className(), ['id' => 'price_cat_id']);
    }

    /**
    * Categories
    */
    public function getTypeName() {
        switch ($this->type) {
            case self::TYPE_CAT:
                return 'Тип товара';
                break;
            case self::TYPE_FASON:
                return 'Фасон';
                break;
            case self::TYPE_FEATURES:
                return 'Особенности';
                break;
            case self::TYPE_OCCASION:
                return 'Повод';
                break;
            case self::TYPE_COLOR:
                return 'Цвет';
                break;
            case self::TYPE_PRICE:
                return 'Цена';
                break;
            default:
                return '-';
                break;
        }
    }

    public function getTypeArr() {
        return [
            self::TYPE_CAT => 'Тип товара',
            self::TYPE_FASON => 'Фасон',
            self::TYPE_FEATURES => 'Особенности',
            self::TYPE_OCCASION => 'Повод',
            self::TYPE_COLOR => 'Цвет',
            self::TYPE_PRICE => 'Цена',
        ];
    }
}
