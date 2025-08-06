<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\SalesTripSlaves;

/**
 * SalesTripSlavesSearch represents the model behind the search form of `backend\models\SalesTripSlaves`.
 */
class SalesTripSlavesSearch extends SalesTripSlaves
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'sale_id'], 'integer'],
            [['created_at','serial_no'], 'safe'],
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
        $query = SalesTripSlaves::find();

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
            'sale_id' => $this->sale_id,
            'serial_no' => $this->serial_no,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
