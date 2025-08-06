<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\TraWebComparrison;

/**
 * TraWebComparrisonSearch represents the model behind the search form of `backend\models\TraWebComparrison`.
 */
class TraWebComparrisonSearch extends TraWebComparrison
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tra_count', 'web_count', 'count_status', 'compared_by', 'status', 'route_id','branch'], 'integer'],
            [['serial_no', 'tzdl', 'datetime'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = TraWebComparrison::find();

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
            'tra_count' => $this->tra_count,
            'web_count' => $this->web_count,
            'count_status' => $this->count_status,
            'compared_by' => $this->compared_by,
            'datetime' => $this->datetime,
            'status' => $this->status,
            'route_id' => $this->route_id,
            'branch' => $this->branch,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'tzdl', $this->tzdl]);

        return $dataProvider;
    }
    public function searchById($params)
    {
        $query = TraWebComparrison::find()
            ->where(['route_id'=>$params]);

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
            'tra_count' => $this->tra_count,
            'web_count' => $this->web_count,
            'count_status' => $this->count_status,
            'compared_by' => $this->compared_by,
            'datetime' => $this->datetime,
            'status' => $this->status,
            'route_id' => $this->route_id,
            'branch' => $this->branch,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'tzdl', $this->tzdl]);

        return $dataProvider;
    }
}
