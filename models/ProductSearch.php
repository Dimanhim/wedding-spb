<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'marka_id', 'model_id', 'color_id', 'ratio_id', 'created_at', 'updated_at'], 'integer'],
            [['description', 'photo'], 'safe'],
            [['purchase_price_small', 'purchase_price_big', 'purchase_price_small_dol', 'purchase_price_big_dol', 'recommended_price_small', 'recommended_price_big', 'price_small', 'price_big'], 'number'],
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
        $query = Product::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'marka_id' => $this->marka_id,
            'model_id' => $this->model_id,
            'color_id' => $this->color_id,
            'purchase_price_small' => $this->purchase_price_small,
            'purchase_price_big' => $this->purchase_price_big,
            'purchase_price_small_dol' => $this->purchase_price_small_dol,
            'purchase_price_big_dol' => $this->purchase_price_big_dol,
            'recommended_price_small' => $this->recommended_price_small,
            'recommended_price_big' => $this->recommended_price_big,
            'price_small' => $this->price_small,
            'price_big' => $this->price_big,
            'ratio_id' => $this->ratio_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'photo', $this->photo]);

        return $dataProvider;
    }

    public function multi_array_search($array, $search)
    {
        foreach ($array as $key => $value) {
            foreach ($search as $k => $v) {
                if (!isset($value[$k]) || $value[$k] != $v) continue 2;
            }
            return $key;
        }
        return false;
    }
}
