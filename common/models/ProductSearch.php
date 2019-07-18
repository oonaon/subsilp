<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Product;

/**
 * CompanySearch represents the model behind the search form of `common\models\Company`.
 */
class ProductSearch extends Product {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'category_id', 'unit_id'], 'integer'],
            [['org', 'code', 'kind', 'caption', 'detail', 'images', 'alias', 'memo', 'status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
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
    public function search($params) {
        $query = Product::find();
        $session = Yii::$app->session;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
            'category_id' => $this->category_id,
        ]);

        $query->andFilterWhere(['like', 'org', $session['organize']]);

        $query->andFilterWhere(['like', 'org', $this->org])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'kind', $this->kind])
                ->andFilterWhere(['like', 'caption', $this->caption])
                ->andFilterWhere(['like', 'detail', $this->detail])
                ->andFilterWhere(['like', 'alias', $this->alias])
                ->andFilterWhere(['like', 'memo', $this->memo])
                ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}
