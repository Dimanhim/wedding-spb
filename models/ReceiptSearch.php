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
            'payment_type' => $this->payment_type,
            'total_amount' => $this->total_amount,
            'manager_id' => $this->manager_id,
            'price' => $this->price,
            'sale' => $this->sale,
            'total_price' => $this->total_price,
            'change' => $this->change,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($created_at_begin_unix = strtotime($this->created_at_begin)) {
            $query->andFilterWhere(['>=', 'created_at', $created_at_begin_unix]);
        }
        if ($created_at_end_unix = strtotime($this->created_at_end)) {
            $query->andFilterWhere(['<=', 'created_at', ($created_at_end_unix + 86399)]);
        }

        return $dataProvider;
    }

}
