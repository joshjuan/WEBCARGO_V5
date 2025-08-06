<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\InTransitSlave;

/**
 * InTransitSlaveSearch represents the model behind the search form of `frontend\models\InTransitSlave`.
 */
class InTransitSlaveSearch extends InTransitSlave
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'serial_no', 'intansit_id', 'branch'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = InTransitSlave::find();

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
            'serial_no' => $this->serial_no,
            'intansit_id' => $this->intansit_id,
            'branch' => $this->branch,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
