<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\AllocatedDevice;

/**
 * AllocatedDeviceSearch represents the model behind the search form of `backend\models\AllocatedDevice`.
 */
class AllocatedDeviceSearch extends AllocatedDevice
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'allocated_to', 'allocated_by'], 'integer'],
            [['allocated_date','serial_no'], 'safe'],
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
        $query = AllocatedDevice::find();

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
            'allocated_date' => $this->allocated_date,
            'allocated_to' => $this->allocated_to,
            'allocated_by' => $this->allocated_by,
        ]);

        return $dataProvider;
    }
}
