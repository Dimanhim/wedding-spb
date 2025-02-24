<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReceiptItem;
use app\models\Manager;

/**
 * ReceiptProductsSearch represents the model behind the search form about `app\models\ReceiptItem`.
 */
class ReceiptProductsSearch extends ReceiptItem
{
    public $created_at_begin;
    public $created_at_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['receipt_id', 'product_id', 'amount', 'price', 'total_price', 'sale', 'gift', 'created_at', 'updated_at', 'manager_id', 'marka_id', 'category_id', 'product_type'], 'integer'],
            [['created_at_begin', 'created_at_end'], 'safe'],
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
        $query = ReceiptItem::find()
            ->select('`receipt_items`.*, `receipts`.`manager_id`, 
                `products`.`marka_id`, `products`.`category_id`, `products`.`purchase_price`, `products`.`purchase_price_small`, `products`.`purchase_price_big`, 
                `colors`.`name` AS color_name, `marks`.`name` AS marka_name, `models`.`name` AS model_name')
            ->leftJoin('receipts', 'receipts.id = receipt_items.receipt_id')
            ->leftJoin('products', 'products.id = receipt_items.product_id')
            ->leftJoin('colors', 'colors.id = products.color_id')
            ->leftJoin('marks', 'marks.id = products.marka_id')
            ->leftJoin('models', 'models.id = products.model_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 0,
            ],
        ]);

        $dataProvider->sort->attributes['manager_id'] = [
            'asc' => ['receipts.manager_id' => SORT_ASC],
            'desc' => ['receipts.manager_id' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (!$this->created_at_begin) $this->created_at_begin = date('01.m.Y');
        if (!$this->created_at_end) $this->created_at_end = date('t.m.Y');

        if ($this->manager_id) $query->andFilterWhere(['receipts.manager_id' => $this->manager_id]);
        if ($this->marka_id) $query->andFilterWhere(['products.marka_id' => $this->marka_id]);
        if ($this->category_id) $query->andFilterWhere(['products.category_id' => $this->category_id]);

        if ($this->product_type == 1) $query->andFilterWhere(['in', 'products.category_id', [1, 2]]);
        if ($this->product_type == 2) $query->andFilterWhere(['not in', 'products.category_id', [1, 2]]);

        $query->andFilterWhere(['>=', 'receipt_items.created_at', strtotime($this->created_at_begin)]);
        $query->andFilterWhere(['<=', 'receipt_items.created_at', strtotime($this->created_at_end) + 86399]);

        return $dataProvider;
    }

}
