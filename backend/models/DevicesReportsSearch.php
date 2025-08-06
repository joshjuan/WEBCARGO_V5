<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\DevicesReports;

/**
 * DevicesReportsSearch represents the model behind the search form of `backend\models\DevicesReports`.
 */
class DevicesReportsSearch extends DevicesReports
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'received_from', 'received_to', 'border_port', 'received_from_staff', 'received_status', 'received_by', 'created_by', 'branch', 'type', 'device_category', 'released_by', 'released_to', 'transferred_from', 'transferred_to', 'transferred_by', 'sales_person', 'sale_id', 'view_status', 'registration_by'], 'integer'],
            [['serial_no', 'sim_card', 'received_at', 'remark', 'created_at', 'transferred_date', 'released_date', 'tzl', 'vehicle_no', 'container_number', 'registration_date', 'movement_unique_id'], 'safe'],
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
        $query = DevicesReports::find();
           // ->where(['branch' => Yii::$app->user->identity->branch]);

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
            'received_from' => $this->received_from,
            'received_to' => $this->received_to,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'received_at' => $this->received_at,
            'received_status' => $this->received_status,
            'received_by' => $this->received_by,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'branch' => $this->branch,
            'type' => $this->type,
            'device_category' => $this->device_category,
            'released_by' => $this->released_by,
            'released_to' => $this->released_to,
            'transferred_from' => $this->transferred_from,
            'transferred_to' => $this->transferred_to,
            'transferred_date' => $this->transferred_date,
            'transferred_by' => $this->transferred_by,
            'released_date' => $this->released_date,
            'sales_person' => $this->sales_person,
            'sale_id' => $this->sale_id,
            'view_status' => $this->view_status,
            'registration_date' => $this->registration_date,
            'registration_by' => $this->registration_by,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['like', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'container_number', $this->container_number])
            ->andFilterWhere(['like', 'movement_unique_id', $this->movement_unique_id]);

        return $dataProvider;
    }
}
