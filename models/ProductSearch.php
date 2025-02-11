<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Product;
use app\models\Amount;

/**
 * ProductSearch represents the model behind the search form about `app\models\Product`.
 */
class ProductSearch extends Product
{
    public $size_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'category_id', 'marka_id', 'model_id', 'color_id', 'ratio_id', 'created_at', 'updated_at', 'is_deleted', 'position'], 'integer'],
            [['description', 'photo', 'name', 'size_id'], 'safe'],
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
        //В зале и на складе
        if (Yii::$app->request->get('type') == 'warehouse' or Yii::$app->request->get('type') == 'hall') {
            $amount_type = Amount::TYPE_WAREHOUSE;
            if (Yii::$app->request->get('type') == 'hall') $amount_type = Amount::TYPE_HALL;
            $query = Product::find();
            $query->joinWith(['model']);
            $query  ->leftJoin('amounts', '`products`.`id` = `amounts`.`product_id`')
                    ->where(['products.category_id' => $params['category_id']])
                    ->andWhere(['products.is_deleted' => 0])
                    ->andWhere(['amounts.amount_type' => $amount_type])
                    ->andWhere(['>', 'amounts.amount', 0])
                    ->groupBy(['products.id']);
            //SELECT * FROM `products` LEFT JOIN `amounts` ON `products`.`id` = `amounts`.`product_id` WHERE (`products`.`category_id`='1') AND (`products`.`is_deleted`=0) AND (`amounts`.`amount_type` = 1) AND (`amounts`.`amount` > 0) GROUP BY `products`.`id`
            
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'sort'=> ['defaultOrder' => ['id' => SORT_DESC]],
                'pagination' => [
                    'pageSize' => 0,
                ],
            ]);

            $dataProvider->sort->attributes['model_id'] = [
                'asc' => ['models.name' => SORT_ASC],
                'desc' => ['models.name' => SORT_DESC],
            ];

            $this->load($params);
            
            $query->andFilterWhere([
                'marka_id' => $this->marka_id,
                'model_id' => $this->model_id,
                'color_id' => $this->color_id,
            ]);
            
            $query->andFilterWhere(['like', 'name', $this->name]);

            return $dataProvider;
        }

        //Поиск по всем товарам
        $query = Product::find()->where(['products.category_id' => $params['category_id']]);
        $query->joinWith(['model']);

        if (Yii::$app->request->get('is_deleted') == 1) {
            $query->andWhere(['products.is_deleted' => 1]);
        } else {
            $query->andWhere(['products.is_deleted' => 0]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['products.id' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 100,
            ],
        ]);

        $dataProvider->sort->attributes['model_id'] = [
            'asc' => ['models.name' => SORT_ASC],
            'desc' => ['models.name' => SORT_DESC],
        ];

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->size_id) {
            //Amount::find()->where([''])->all()
            $query  ->leftJoin('amounts', '`products`.`id` = `amounts`.`product_id`')
                    ->andWhere(['amounts.amount_type' => Amount::TYPE_HALL])
                    ->andWhere(['amounts.size_id' => $this->size_id])
                    ->andWhere(['>', 'amounts.amount', 0])
                    ->groupBy(['products.id']);
        }

        $query->andFilterWhere([
            'products.id' => $this->id,
            'products.marka_id' => $this->marka_id,
            'products.model_id' => $this->model_id,
            'products.color_id' => $this->color_id,
            'products.purchase_price_small' => $this->purchase_price_small,
            'products.purchase_price_big' => $this->purchase_price_big,
            'products.purchase_price_small_dol' => $this->purchase_price_small_dol,
            'products.purchase_price_big_dol' => $this->purchase_price_big_dol,
            'products.recommended_price_small' => $this->recommended_price_small,
            'products.recommended_price_big' => $this->recommended_price_big,
            'products.price_small' => $this->price_small,
            'products.price_big' => $this->price_big,
            'products.ratio_id' => $this->ratio_id,
            'products.position' => $this->position,
            'products.created_at' => $this->created_at,
            'products.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'products.description', $this->description])
            ->andFilterWhere(['like', 'products.name', $this->name])
            ->andFilterWhere(['like', 'products.photo', $this->photo]);

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
