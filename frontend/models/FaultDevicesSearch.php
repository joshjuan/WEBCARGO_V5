<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\FaultDevices;

/**
 * FaultDevicesSearch represents the model behind the search form of `backend\models\FaultDevices`.
 */
class FaultDevicesSearch extends FaultDevices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by','view_status'], 'integer'],
            [['created_at', 'serial_no','remarks'], 'safe'],
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
        $query = FaultDevices::find();
        $query->where(['view_status'=>Devices::fault_devices]);

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
          //  'serial_no' => $this->serial_no,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'remarks', $this->remarks]);

        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere( [
                'or',
                [ '=', 'serial_no', $key ],
            ] );
        }

        return $dataProvider;
    }

    public function searchClean($params)
    {
        $query = FaultDevices::find();
        //$query->where(['view_status'=>Devices::fault_devices]);

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
          //  'serial_no' => $this->serial_no,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'view_status' => Devices::fault_devices,
        ]);

        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        $query->andFilterWhere(['in', 'serial_no', $terms]);

        return $dataProvider;
    }
}
