<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\ExtSalesTrips;

/**
 * ExtSalesTripsSearch represents the model behind the search form of `common\models\ExtSalesTrips`.
 */
class ExtSalesTripsSearch extends ExtSalesTrips
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'gate_id', 'border_id', 'cargo_type_id', 'trip_status', 'created_by', 'cancelled_by', 'editted_by'], 'integer'],
            [['customer', 'master', 'slaves', 'trip_no', 'vehicle_no', 'trailer_no', 'merchant_no', 'receipt_no', 'agent', 'driver', 'cargo_no', 'chassis_no', 'vehicle_type', 'container_no', 'device_price', 'start_date', 'end_date', 'created_at', 'branch', 'cancelled_at', 'editted_at'], 'safe'],
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
     * @param string|null $formName Form name to be used into `->load()` method.
     *
     * @return ActiveDataProvider
     */
    public function search($params, $formName = null)
    {
        $query = ExtSalesTrips::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params, $formName);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'gate_id' => $this->gate_id,
            'border_id' => $this->border_id,
            'cargo_type_id' => $this->cargo_type_id,
            'trip_status' => $this->trip_status,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'cancelled_at' => $this->cancelled_at,
            'cancelled_by' => $this->cancelled_by,
            'editted_at' => $this->editted_at,
            'editted_by' => $this->editted_by,
        ]);

        $query->andFilterWhere(['like', 'customer', $this->customer])
            ->andFilterWhere(['like', 'master', $this->master])
            ->andFilterWhere(['like', 'slaves', $this->slaves])
            ->andFilterWhere(['like', 'trip_no', $this->trip_no])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'trailer_no', $this->trailer_no])
            ->andFilterWhere(['like', 'merchant_no', $this->merchant_no])
            ->andFilterWhere(['like', 'receipt_no', $this->receipt_no])
            ->andFilterWhere(['like', 'agent', $this->agent])
            ->andFilterWhere(['like', 'driver', $this->driver])
            ->andFilterWhere(['like', 'cargo_no', $this->cargo_no])
            ->andFilterWhere(['like', 'chassis_no', $this->chassis_no])
            ->andFilterWhere(['like', 'vehicle_type', $this->vehicle_type])
            ->andFilterWhere(['like', 'container_no', $this->container_no])
            ->andFilterWhere(['like', 'device_price', $this->device_price])
            ->andFilterWhere(['like', 'branch', $this->branch]);

        return $dataProvider;
    }
}
