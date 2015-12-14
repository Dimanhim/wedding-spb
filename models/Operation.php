<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "operations".
 *
 * @property integer $id
 * @property string $name
 * @property integer $user_id
 * @property integer $type_id
 * @property integer $cat_id
 * @property integer $payment_type
 * @property double $total_price
 * @property integer $repeated
 * @property string $interval
 * @property string $months
 * @property string $days
 * @property string $week
 * @property integer $created_at
 * @property integer $updated_at
 */
class Operation extends \yii\db\ActiveRecord
{
    const TYPE_INCOME = 1;
    const TYPE_EXPENSE = 2;

    const CAT_BUY           = 1;
    const CAT_SELL          = 2;
    const CAT_SALARY        = 3;
    const CAT_ADVANCE       = 4;
    const CAT_SERVICES      = 5;
    const CAT_ADS           = 6;
    const CAT_ETC_INCOME    = 7;
    const CAT_ETC_EXPENSE   = 8;

    const PAY_CASH = 1;
    const PAY_NOCASH = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operations';
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
            [['name', 'type_id', 'cat_id', 'payment_type', 'total_price'], 'required'],
            [['user_id', 'type_id', 'cat_id', 'payment_type', 'repeated', 'created_at', 'updated_at'], 'integer'],
            [['total_price'], 'number'],
            [['name', 'interval'], 'string', 'max' => 255],
            [['months', 'days', 'week'], 'safe']
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
            'user_id' => 'Пользователь',
            'type_id' => 'Тип',
            'cat_id' => 'Категория',
            'payment_type' => 'Способ оплаты',
            'total_price' => 'Сумма',
            'repeated' => 'Запланирована',
            'interval' => 'Интервал',
            'months' => 'Месяцы',
            'days' => 'Дни месяца',
            'week' => 'Дни недели',
            'created_at' => 'Дата добавления',
            'date_start' => 'Период',
            'date_end' => 'Период',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $months_arr = isset($_POST['Operation']['months']) ? $_POST['Operation']['months'] : "";
            $days_arr = isset($_POST['Operation']['days']) ? $_POST['Operation']['days'] : "";
            $week_arr = isset($_POST['Operation']['week']) ? $_POST['Operation']['week'] : "";

            $months_str = "*";
            $days_str = "*";
            $week_str = "*";
            $interval = "* * * * *";

            if ($days_arr and count($days_arr) != 31) $days_str = implode(',', $days_arr);
            if ($months_arr and count($months_arr) != 12) $months_str = implode(',', $months_arr);
            if ($week_arr and count($week_arr) != 7) $week_str = implode(',', $week_arr);

            $interval = "* * $days_str $months_str $week_str";

            $this->months = $months_str; 
            $this->days = $days_str;  
            $this->week = $week_str;  
            $this->interval = $interval;
            return true;
        } else {
            return false;
        }
    }

    public function allMonths() {
        return [
            1 => 'Январь',
            2 => 'Февраль',
            3 => 'Март',
            4 => 'Апрель',
            5 => 'Май',
            6 => 'Июнь',
            7 => 'Июль',
            8 => 'Август',
            9 => 'Сентябрь',
            10 => 'Октябрь',
            11 => 'Ноябрь',
            12 => 'Декабрь'
        ];
    }

    public function allWeek() {
        return [
            1 => 'Пн',
            2 => 'Вт',
            3 => 'Ср',
            4 => 'Чт',
            5 => 'Пт',
            6 => 'Сб',
            7 => 'Вс'
        ];
    }

    public function allDays()
    {
        $days = [];
        for ($i=1; $i <= 31; $i++) { 
            $days[$i] = $i;
        }
        return $days;
    }

    public function getTypeLabel() {
        switch ($this->type_id) {
            case self::TYPE_INCOME:
                return 'доход';
                break;
            case self::TYPE_EXPENSE:
                return 'расход';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getTypes() {
        return [
            self::TYPE_INCOME => 'доход',
            self::TYPE_EXPENSE => 'расход',
        ];
    }

    public function getCatLabel() {
        switch ($this->cat_id) {
            case self::CAT_BUY:
                return 'заказы';
                break;
            case self::CAT_SELL:
                return 'продажи';
                break;
            case self::CAT_SALARY:
                return 'ЗП';
                break;
            case self::CAT_ADVANCE:
                return 'аванс';
                break;
            case self::CAT_SERVICES:
                return 'услуги';
                break;
            case self::CAT_ADS:
                return 'реклама';
                break;
            case self::CAT_ETC_INCOME:
                return 'прочие доходы';
                break;
            case self::CAT_ETC_EXPENSE:
                return 'прочие расходы';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getCategories() {
        return [
            self::CAT_BUY => 'заказы',
            self::CAT_SELL => 'продажи',
            self::CAT_SALARY => 'ЗП',
            self::CAT_ADVANCE => 'аванс',
            self::CAT_SERVICES => 'услуги',
            self::CAT_ADS => 'реклама',
            self::CAT_ETC_INCOME => 'прочие доходы',
            self::CAT_ETC_EXPENSE => 'прочие расходы',
        ];
    }

    public function getPayLabel() {
        switch ($this->payment_type) {
            case self::PAY_CASH:
                return 'наличными';
                break;
            case self::PAY_NOCASH:
                return 'картой';
                break;
            default:
                return 'неизвестен';
                break;
        }
    }

    public function getPayments() {
        return [
            self::PAY_CASH => 'наличными',
            self::PAY_NOCASH => 'картой',
        ];
    }


    public function createFromCron() {
        $schedules = Operation::find()->where(['repeated' => 1])->all();
        $day = date("j");
        $month = date("n");
        $week = date("N");
        $total_created = 0;
        foreach ($schedules as $schedule) {
            $day_ok = false;
            $month_ok = false;
            $week_ok = false; 
            $cron_parts = explode(' ', $schedule->interval);

            //Дни
            if ($cron_parts[2] == '*') {
                $day_ok = true;
            } else {
                $days = explode(',', $cron_parts[2]);
                if (in_array($day, $days)) $day_ok = true;
            }

            //Месяцы
            if ($cron_parts[3] == '*') {
                $month_ok = true;
            } else {
                $months = explode(',', $cron_parts[3]);
                if (in_array($month, $months)) $month_ok = true;
            }

            //Дни недели
            if ($cron_parts[4] == '*') {
                $week_ok = true;
            } else {
                $weeks = explode(',', $cron_parts[4]);
                if (in_array($week, $weeks)) $week_ok = true;
            }

            if($day_ok and $month_ok and $week_ok) {
                //Добавление в учет
                $operation = new Operation();
                $operation->attributes = $schedule->attributes;
                $operation->repeated = 0;
                $operation->created_at = time();
                $operation->updated_at = time();
                if ($operation->save()) {
                    $total_created++;
                }
            }
        }
        return 'Создано операций: '.$total_created;
    }

}
