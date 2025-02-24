<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "clients".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property string $fio
 * @property string $phone
 * @property string $email
 * @property integer $first_visit
 * @property string $visit_purpose
 * @property integer $is_appoint
 * @property integer $birtday
 * @property integer $event_date
 * @property string $sizes
 * @property string $wedding_place
 * @property integer $source
 * @property string $description
 * @property string $ip
 * @property integer $created_at
 * @property integer $updated_at
 */
class Client extends \yii\db\ActiveRecord
{
    public $first_visit_field;
    public $birtday_field;
    public $event_date_field;
    public $sizes_field;
    public $products_field;

    const SOURCE_STREET     = 1;
    const SOURCE_WEB        = 2;
    const SOURCE_FRIENDS    = 3;
    const SOURCE_OTHER      = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'clients';
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
            [['fio', 'phone'], 'required'],
            [['phone', 'email'], 'unique'],
            [['manager_id', 'first_visit', 'is_appoint', 'birtday', 'event_date', 'source'], 'integer'],
            [['first_visit_field', 'birtday_field', 'event_date_field', 'sizes_field', 'products_field'], 'safe'],
            [['description'], 'string'],
            [['fio', 'phone', 'email', 'visit_purpose', 'wedding_place', 'sizes', 'ip'], 'string', 'max' => 255],
        ];
    }

    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
    }

    public function getPrimerki()
    {
        return $this->hasMany(Primerka::className(), ['client_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(ClientProduct::className(), ['client_id' => 'id']);
    }

    public function getFormattedSizes()
    {
        $sizes = [];
        foreach (explode(',', $this->sizes) as $size_id) {
            $sizes[] = Size::findOne($size_id)->name;
        }
        return implode(',', $sizes);
    }

    public function getShortPhone() {
        $prefix_replace = str_replace('+7', '8', $this->phone);
        return str_replace(['(', ')', ' ', '-'], '', $prefix_replace);
    }

    public function getFormattedProducts()
    {
        $products = [];
        foreach ($this->products as $client_product) {
            $products[] = Html::a($client_product->product->name, ['products/view', 'id' => $client_product->product_id]);
        }
        return implode('<br>', $products);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => 'Менеджер',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'email' => 'Email',
            'first_visit' => 'Первый визит',
            'first_visit_field' => 'Первый визит',
            'visit_purpose' => 'Цель визита',
            'is_appoint' => 'По записи',
            'birtday' => 'Дата рождения',
            'birtday_field' => 'Дата рождения',
            'event_date' => 'Дата события',
            'event_date_field' => 'Дата события',
            'sizes' => 'Размеры',
            'sizes_field' => 'Размеры',
            'products_field' => 'Купленные товары',
            'wedding_place' => 'Где свадьба',
            'source' => 'Откуда узнали',
            'description' => 'Примечание',
            'ip' => 'IP',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
    * Sources
    */
    public function getSourceName() {
        switch ($this->source) {
            case self::SOURCE_STREET:
                return 'Витрина';
                break;
            case self::SOURCE_WEB:
                return 'Интернет';
                break;
            case self::SOURCE_FRIENDS:
                return 'Рекомендации';
                break;
            case self::SOURCE_OTHER:
                return 'Другое';
                break;
            default:
                return '-';
                break;
        }
    }

    public function getSourceArr() {
        return [
            self::SOURCE_STREET => 'Витрина',
            self::SOURCE_WEB => 'Интернет',
            self::SOURCE_FRIENDS => 'Рекомендации',
            self::SOURCE_OTHER => 'Другое',
        ];
    }
}
