<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Receipt;

/**
 * ReceiptSearch represents the model behind the search form about `app\models\Receipt`.
 */
class ReceiptSearch extends Receipt
{
    public $created_at_begin;
    public $created_at_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'payment_type', 'total_amount', 'manager_id', 'created_at', 'updated_at'], 'integer'],
            [['created_at_begin', 'created_at_end'], 'safe'],
            [['price', 'sale', 'total_price', 'change'], 'number']
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
        $query = Receipt::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!$this->created_at_begin) $this->created_at_begin = date('01.m.Y');
        if (!$this->created_at_end) $this->created_at_end = date('t.m.Y');

        //if (!$this->created_at_begin) $this->created_at_begin = '01.01.2023';
        //if (!$this->created_at_end) $this->created_at_end = date('t.m.Y');

        $query->andFilterWhere(['>=', 'created_at', strtotime($this->created_at_begin)]);
        $query->andFilterWhere(['<=', 'created_at', strtotime($this->created_at_end) + 86399]);

        $query->andFilterWhere([
            // 'id' => $this->id,
            // 'payment_type' => $this->payment_type,
            // 'total_amount' => $this->total_amount,
            'manager_id' => $this->manager_id,
            // 'price' => $this->price,
            // 'sale' => $this->sale,
            // 'total_price' => $this->total_price,
            // 'change' => $this->change,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
        ]);

        return $dataProvider;
    }

}
