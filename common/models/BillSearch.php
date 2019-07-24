<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Bill;

/**
 * CompanySearch represents the model behind the search form of `common\models\Company`.
 */
class BillSearch extends Bill {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id','company_id','ref_id','credit','discount_percent','vat_percent','location_id','contact_id'], 'integer'],
            [['org', 'code', 'type','date', 'vat_type', 'reference', 'duedate', 'pre_total', 'discount_total', 'sub_total',
                'amount_novat', 'amount_vat', 'vat_total', 'total', 'remark', 'transport', 'salesman','status'], 'safe'],
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
        $type=Bill::getTypeFromController();
        
        $query = Bill::find();
        $session = Yii::$app->session;

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['code'=>SORT_DESC]],
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
        ]);

        $query->andFilterWhere(['like', 'org', $session['organize']]);

        $query->andFilterWhere(['like', 'org', $this->org])
                ->andFilterWhere(['like', 'code', $this->code])

                ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }

}
