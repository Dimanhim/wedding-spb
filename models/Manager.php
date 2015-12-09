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
 * @property integer $vacation_start
 * @property integer $vacation_end
 * @property integer $advance_date
 * @property integer $salary_date
 * @property integer $created_at
 * @property integer $updated_at
 */
class Manager extends \yii\db\ActiveRecord
{
    public $email;
    public $password;
    public $password_copy;

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

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['user_id', 'name', 'surname', 'employment_date', 'email', 'password', 'password_copy'];
        return $scenarios;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'surname', 'employment_date', 'email', 'password', 'password_copy'], 'required', 'on' => 'create'],
            [['user_id', 'employment_date', 'created_at', 'updated_at', 'vacation_start', 'vacation_end', 'salary_date', 'advance_date'], 'integer'],
            [['name', 'surname', 'fathername'], 'string', 'max' => 255],
            ['email', 'email'],
            ['password_copy', 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
            [['password_copy', 'password'], 'string', 'min' => 6, 'tooShort' => 'Пароль должен содержать не менее 6 символов'],
        ];
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
            'email' => 'Email (логин)',
            'password' => 'Пароль',
            'password_copy' => 'Повторите пароль',
            'employment_date' => 'Дата приёма на работу',
            'vacation_start' => 'Начало отпуска',
            'vacation_end' => 'Конец отпуска',
            'advance_date' => 'Дата аванса',
            'salary_date' => 'Дата ЗП',
            'created_at' => 'Дата добавления',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
    * Relations
    */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

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
