<?php

namespace app\models;

use Yii;
use yii\helpers\Html;
use yii\behaviors\TimestampBehavior;
use codeonyii\yii2validators\AtLeastValidator;

/**
 * This is the model class for table "primerki".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $client_id
 * @property integer $date
 * @property string $description
 * @property integer $result
 * @property integer $receipt_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class Primerka extends \yii\db\ActiveRecord
{
    public $date_field;
    public $wishes_field;
    public $products_field;
    public $client_fio;
    public $client_phone;

    const RESULT_PRIMERKA   = 1;
    const RESULT_INTERES    = 2;
    const RESULT_SELL       = 3;
    const RESULT_NOT_COME   = 4;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'primerki';
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
            //[['client_id', 'date'], 'required'],
            [['date'], 'required'],
            ['client_id', AtLeastValidator::className(), 'in' => ['client_id', 'client_fio', 'client_phone']],
            [['date_field', 'wishes_field', 'products_field'], 'safe'],
            [['manager_id', 'client_id', 'date', 'result', 'receipt_id'], 'integer'],
            [['description'], 'string'],
            [['client_fio', 'client_phone'], 'string', 'max' => 255],
        ];
    }

    public function getManager()
    {
        return $this->hasOne(Manager::className(), ['id' => 'manager_id']);
    }

    public function getClient()
    {
        return $this->hasOne(Client::className(), ['id' => 'client_id']);
    }

    public function getReceipt()
    {
        return $this->hasOne(Receipt::className(), ['id' => 'receipt_id']);
    }

    public function getWishes()
    {
        return $this->hasMany(PrimerkaWish::className(), ['primerka_id' => 'id']);
    }

    public function getProducts()
    {
        return $this->hasMany(PrimerkaProduct::className(), ['primerka_id' => 'id']);
    }

    public function getFormattedProducts()
    {
        $products = [];
        foreach ($this->products as $primerka_product) {
            $products[] = $primerka_product->product->name;
            //$products[] = Html::a($primerka_product->product->name, ['products/view', 'id' => $primerka_product->product_id]);
        }
        return implode('<br>', $products);
    }

    public function getFormattedWishes()
    {
        $products = [];
        foreach ($this->wishes as $primerka_product) {
            $products[] = $primerka_product->product->name;
            //$products[] = Html::a($primerka_product->product->name, ['products/view', 'id' => $primerka_product->product_id]);
        }
        return implode('<br>', $products);
    }

    public function newClient()
    {
        if (!$this->client_id) {
            $client = new Client();
            $client->manager_id = $this->manager_id;
            $client->fio = $this->client_fio;
            $client->phone = $this->client_phone;
            if ($client->save()) {
                $this->client_id = $client->id;
                return true;
            } else {
                return false;
            }
        }
        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manager_id' => 'Менеджер',
            'client_id' => 'Клиент',
            'client_fio' => 'ФИО',
            'client_phone' => 'Телефон',
            'wishes_field' => 'Избранные товары',
            'products_field' => 'Примеряемые товары',
            'date' => 'Дата примерки',
            'date_field' => 'Дата примерки',
            'description' => 'Примечание',
            'result' => 'Итог',
            'receipt_id' => 'Чек',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата изменения',
        ];
    }

    /**
    * Results
    */
    public function getResultName() {
        switch ($this->result) {
            case self::RESULT_PRIMERKA:
                return 'Примерка';
                break;
            case self::RESULT_INTERES:
                return 'Заинтересованность';
                break;
            case self::RESULT_SELL:
                return 'Продажа';
                break;
            case self::RESULT_NOT_COME:
                return 'Не пришла';
                break;
            default:
                return '-';
                break;
        }
    }

    public function getResultArr() {
        return [
            self::RESULT_PRIMERKA => 'Примерка',
            self::RESULT_INTERES => 'Заинтересованность',
            self::RESULT_SELL => 'Продажа',
            self::RESULT_NOT_COME => 'Не пришла',
        ];
    }
}
