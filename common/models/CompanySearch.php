<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Company;

/**
 * CompanySearch represents the model behind the search form of `common\models\Company`.
 */
class CompanySearch extends Company {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'branch', 'credit', 'salesman', 'transport'], 'integer'],
            [['org', 'code', 'prefix', 'name', 'suffix', 'tax', 'tel', 'fax', 'email', 'website', 'address', 'subdistrict', 'district', 'province', 'postcode', 'payment', 'memo', 'rank', 'status'], 'safe'],
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
        $query = Company::find();
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
            'branch' => $this->branch,
            'credit' => $this->credit,
            'salesman' => $this->salesman,
            'transport' => $this->transport,
            'org' => $session['organize'],
        ]);

        $query->andFilterWhere(['like', 'org', $this->org])
                ->andFilterWhere(['like', 'code', $this->code])
                ->andFilterWhere(['like', 'prefix', $this->prefix])
                ->andFilterWhere(['like', 'name', $this->name])
                ->andFilterWhere(['like', 'suffix', $this->suffix])
                ->andFilterWhere(['like', 'tax', $this->tax])
                ->andFilterWhere(['like', 'tel', $this->tel])
                ->andFilterWhere(['like', 'fax', $this->fax])
                ->andFilterWhere(['like', 'email', $this->email])
                ->andFilterWhere(['like', 'website', $this->website])
                ->andFilterWhere(['like', 'address', $this->address])
                ->andFilterWhere(['like', 'subdistrict', $this->subdistrict])
                ->andFilterWhere(['like', 'district', $this->district])
                ->andFilterWhere(['like', 'province', $this->province])
                ->andFilterWhere(['like', 'postcode', $this->postcode])
                ->andFilterWhere(['like', 'payment', $this->payment])
                ->andFilterWhere(['like', 'memo', $this->memo])
                ->andFilterWhere(['like', 'rank', $this->rank])
                ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}