<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use app\models\Receipt;

/**
 * This is the model class for table "managers".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $surname
 * @property string $fathername
 * @property integer $employment_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class Manager extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'managers';
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
            [['user_id', 'name', 'surname', 'employment_date'], 'required'],
            [['user_id', 'employment_date', 'created_at', 'updated_at'], 'integer'],
            [['name', 'surname', 'fathername'], 'string', 'max' => 255]
        ];
    }

    /**
    * Relations
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Id',
            'user_id' => 'Пользователь',
            'fio' => 'ФИО',
            'workMonths' => 'Месяцы работы',
            'receiptsNum' => 'Чеки',
            'receiptsSum' => 'Сумма чеков',
            'receiptsAvg' => 'Ср. за месяц',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'fathername' => 'Отчество',
            'employment_date' => 'Дата приёма на работу',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
    * Relations
    */
    public function getReceipts()
    {
        return $this->hasMany(Receipt::className(), ['manager_id' => 'id']);
    }

    // Helpers
    public function getFio()
    {
        return $this->surname.' '.$this->name;
    }

    public function getReceiptsNum()
    {
        return count($this->receipts);
    }

    public function getReceiptsSum()
    {
        $sum = 0;
        foreach ($this->receipts as $receipt) {
            $sum += $receipt->total_price;
        }
        return $sum;
    }

    public function getWorkMonths()
    {
        $start = new \DateTime();
        $now = new \DateTime();

        $start->setTimestamp($this->employment_date);
        $now->setTimestamp(time());

        $interval = $now->diff($start);
        $months = $interval->format('%m');
        return $months ? $months : $months + 1;    }

    public function getReceiptsAvg()
    {
        return round($this->receiptsSum / $this->workMonths);
    }

}
