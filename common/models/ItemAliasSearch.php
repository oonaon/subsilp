<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ItemAlias;

/**
 * CompanySearch represents the model behind the search form of `common\models\Company`.
 */
class ItemAliasSearch extends ItemAlias {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['category', 'val', 'label', 'sort_order', 'status'], 'safe'],
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
    public function search($params,$type) {
        $query = ItemAlias::find();
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
            'category' => $type,
        ]);

        $query->andFilterWhere(['like', 'val', $this->val])
                ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }

}