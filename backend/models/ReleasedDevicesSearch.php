<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReleasedDevices;

/**
 * ReleasedDevicesSearch represents the model behind the search form of `backend\models\ReleasedDevices`.
 */
class ReleasedDevicesSearch extends ReleasedDevices
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'released_by', 'released_to', 'sales_point', 'transferred_from', 'transferred_to', 'transferred_by', 'status', 'view_status'], 'integer'],
            [['released_date', 'transferred_date','serial_no'], 'safe'],
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
        $query = ReleasedDevices::find();
        $query->where(['view_status'=>Devices::released_devices]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'released_date' => SORT_DESC,
                ],
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
            'id' => $this->id,
          //  'serial_no' => $this->serial_no,
            'released_date' => $this->released_date,
            'released_by' => $this->released_by,
            'released_to' => $this->released_to,
            'sales_point' => $this->sales_point,
            'transferred_from' => $this->transferred_from,
            'transferred_to' => $this->transferred_to,
            'transferred_date' => $this->transferred_date,
            'transferred_by' => $this->transferred_by,
            'status' => $this->status,
            'view_status' => $this->view_status,
        ]);

        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere( [
                'or',
                [ '=', 'serial_no', $key ],
            ] );
        }
        return $dataProvider;
    }

    public function searchSearch($params)
    {
        $query = ReleasedDevices::find();
       // $query->where(['view_status'=>Devices::released_devices]);
        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
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
            'id' => $this->id,
          //  'serial_no' => $this->serial_no,
            'released_date' => $this->released_date,
            'released_by' => $this->released_by,
            'released_to' => $this->released_to,
            'sales_point' => $this->sales_point,
            'transferred_from' => $this->transferred_from,
            'transferred_to' => $this->transferred_to,
            'transferred_date' => $this->transferred_date,
            'transferred_by' => $this->transferred_by,
            'status' => $this->status,
            'view_status' => Devices::released_devices,
        ]);


        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        $query->andFilterWhere(['in', 'serial_no', $terms]);
        return $dataProvider;
    }
}
