<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Order;
use app\models\OrderItem;

/**
 * OrderSearch represents the model behind the search form about `app\models\Order`.
 */
class OrderSearch extends Order
{
    public $created_at_begin;
    public $created_at_end;
    public $await_date_begin;
    public $await_date_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'await_date', 'payment_type', 'total_amount', 'payment_status', 'delivery_status', 'created_at', 'updated_at', 'accepted', 'marka_id'], 'integer'],
            [['created_at_begin', 'created_at_end', 'await_date_begin', 'await_date_end'], 'safe'],
            [['total_payed', 'total_rest', 'total_price'], 'number'],
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
        //$query = Order::find()->select('`orders`.*, `order_items`.`product_id`')->leftJoin('order_items', 'orders.id = order_items.order_id');
        $query = Order::find();
        
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

        if ($this->marka_id) {
            $order_ids = OrderItem::find()->select('order_id')->where(['marka_id' => $this->marka_id])->distinct()->asArray()->column();
            $query->andFilterWhere(['in', 'id', $order_ids]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'await_date' => $this->await_date,
            'payment_type' => $this->payment_type,
            'total_amount' => $this->total_amount,
            'payment_status' => $this->payment_status,
            'delivery_status' => $this->delivery_status,
            'total_payed' => $this->total_payed,
            'total_rest' => $this->total_rest,
            'total_price' => $this->total_price,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        if ($created_at_begin_unix = strtotime($this->created_at_begin)) {
            $query->andFilterWhere(['>=', 'created_at', $created_at_begin_unix]);
        }
        if ($created_at_end_unix = strtotime($this->created_at_end)) {
            $query->andFilterWhere(['<=', 'created_at', ($created_at_end_unix + 86399)]);
        }
        if ($await_date_begin_unix = strtotime($this->await_date_begin)) {
            $query->andFilterWhere(['>=', 'await_date', $await_date_begin_unix]);
        }
        if ($await_date_end_unix = strtotime($this->await_date_end)) {
            $query->andFilterWhere(['<=', 'await_date', ($await_date_end_unix + 86399)]);
        }

        return $dataProvider;
    }

}
