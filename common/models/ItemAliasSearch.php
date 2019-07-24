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
    public function search($params,$category) {
        $query = ItemAlias::find();
        $session = Yii::$app->session;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['sort_order'=>SORT_ASC]],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'category' => $category,
        ]);

        $query->andFilterWhere(['like', 'val', $this->val])
                ->andFilterWhere(['like', 'category', $this->category])
                ->andFilterWhere(['like', 'label', $this->label]);

        return $dataProvider;
    }

}
