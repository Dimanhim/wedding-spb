<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Operation;

/**
 * OperationSearch represents the model behind the search form about `app\models\Operation`.
 */
class OperationSearch extends Operation
{
    public $date_start;
    public $date_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'type_id', 'cat_id', 'payment_type', 'repeated', 'created_at', 'updated_at'], 'integer'],
            [['total_price'], 'number'],
            [['name', 'interval'], 'string'],
            [['months', 'days', 'week'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Operation::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 30,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'type_id' => $this->type_id,
            //'cat_id' => $this->cat_id,
            'payment_type' => $this->payment_type,
            'repeated' => $this->repeated,
            'total_price' => $this->total_price,
            'name' => $this->name,
            'interval' => $this->interval,
            'months' => $this->months,
            'days' => $this->days,
            'week' => $this->week,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if (!$this->date_start) {
            $date_start = strtotime(date('Y-m-01'));
        } else {
            $date_start = strtotime($this->date_start);
        }
        if (!$this->date_end) {
            $date_end = strtotime(date('Y-m-t'));
        } else {
            $date_end = strtotime($this->date_end) + 86399;
        }

        $query->andFilterWhere(['>=', 'created_at', $date_start]);
        $query->andFilterWhere(['<=', 'created_at', $date_end]);


        return $dataProvider;
    }

}
