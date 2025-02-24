<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\StaticPage;

/**
 * StaticPagesSearch represents the model behind the search form about `app\models\StaticPage`.
 */
class StaticPagesSearch extends StaticPage
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'parent_id', 'fashion_id', 'occasion_id', 'feature_id', 'color_id', 'price_cat_id', 'min_price', 'max_price', 'is_deleted', 'created_at', 'updated_at', 'type', 'category_id'], 'integer'],
            [['name', 'filter_name', 'alias', 'h1', 'title', 'keywords', 'description', 'content'], 'safe'],
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
        $query = StaticPage::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'type' => $this->type,
            'category_id' => $this->category_id,
            'parent_id' => $this->parent_id,
            'fashion_id' => $this->fashion_id,
            'feature_id' => $this->feature_id,
            'occasion_id' => $this->occasion_id,
            'color_id' => $this->color_id,
            'price_cat_id' => $this->price_cat_id,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'filter_name', $this->filter_name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'h1', $this->h1])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'content', $this->content]);

        return $dataProvider;
    }
}
