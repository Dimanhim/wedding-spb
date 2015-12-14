<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HWMove;

/**
 * HWMoveSearch represents the model behind the search form about `app\models\HWMove`.
 */
class HWMoveSearch extends HWMove
{
    public $created_at_begin;
    public $created_at_end;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'total_amount', 'status', 'created_at', 'updated_at'], 'integer'],
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
        $query = HWMove::find();

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
            'total_amount' => $this->total_amount,
            'status' => $this->status,
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
