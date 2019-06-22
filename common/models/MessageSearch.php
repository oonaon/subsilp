<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MessageSource;
use common\models\Message;

/**
 * MessageSearch represents the model behind the search form of `common\models\MessageSource`.
 */
class MessageSearch extends MessageSource {

    public $lang_th, $lang_en;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id'], 'integer'],
            [['category', 'message', 'lang_th', 'lang_en'], 'safe'],
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
        $query = MessageSource::find();
        $query->joinWith(Message::tableName());

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['id'=>SORT_DESC]],
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

        // grid filtering conditions
        $query->andFilterWhere([
            'message_source.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'category', $this->category])
                ->andFilterWhere(['like', 'message', $this->message]);

        if ($this->lang_th) {
            $query->andFilterWhere(['like', 'message.language', 'th'])
                    ->andFilterWhere(['like', 'message.translation', $this->lang_th]);
        }
        if ($this->lang_en) {
            $query->andFilterWhere(['like', 'message.language', 'en'])
                    ->andFilterWhere(['like', 'message.translation', $this->lang_en]);
        }

        return $dataProvider;
    }

}
