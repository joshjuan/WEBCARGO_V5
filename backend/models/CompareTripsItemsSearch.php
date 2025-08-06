<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\CompareTripsItems;

/**
 * CompareTripsItemsSearch represents the model behind the search form of `backend\models\CompareTripsItems`.
 */
class CompareTripsItemsSearch extends CompareTripsItems
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['tzdl', 'serial_no', 'route', 'vehicle_no', 'departure', 'destination', 'vendor', 'cargo_type', 'activation_date', 'activated_by', 'deactivated_by', 'deactivate_date', 'status'], 'safe'],
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
        $query = CompareTripsItems::find();

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
            'activation_date' => $this->activation_date,
            'deactivate_date' => $this->deactivate_date,
        ]);

        $query->andFilterWhere(['like', 'tzdl', $this->tzdl])
            ->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'route', $this->route])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'departure', $this->departure])
            ->andFilterWhere(['like', 'destination', $this->destination])
            ->andFilterWhere(['like', 'vendor', $this->vendor])
            ->andFilterWhere(['like', 'cargo_type', $this->cargo_type])
            ->andFilterWhere(['like', 'activated_by', $this->activated_by])
            ->andFilterWhere(['like', 'deactivated_by', $this->deactivated_by])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
