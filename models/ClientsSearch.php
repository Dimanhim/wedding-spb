<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Client;

/**
 * ClientsSearch represents the model behind the search form about `app\models\Client`.
 */
class ClientsSearch extends Client
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'manager_id', 'first_visit', 'is_appoint', 'birtday', 'event_date', 'source', 'created_at', 'updated_at'], 'integer'],
            [['fio', 'phone', 'email', 'visit_purpose', 'wedding_place', 'description', 'ip'], 'safe'],
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
        $query = Client::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
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
            'manager_id' => $this->manager_id,
            'first_visit' => $this->first_visit,
            'is_appoint' => $this->is_appoint,
            'birtday' => $this->birtday,
            'event_date' => $this->event_date,
            'source' => $this->source,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'fio', $this->fio])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'visit_purpose', $this->visit_purpose])
            ->andFilterWhere(['like', 'wedding_place', $this->wedding_place])
            ->andFilterWhere(['like', 'ip', $this->ip])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
