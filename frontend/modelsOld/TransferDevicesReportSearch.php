<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\TransferDevicesReport;

/**
 * TransferDevicesReportSearch represents the model behind the search form of `frontend\models\TransferDevicesReport`.
 */
class TransferDevicesReportSearch extends TransferDevicesReport
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'released_by', 'released_to', 'sales_point', 'transferred_from', 'transferred_to', 'transferred_by', 'status', 'branch'], 'integer'],
            [['serial_no', 'released_date', 'transferred_date'], 'safe'],
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
        $query = TransferDevicesReport::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100],
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
            'released_date' => $this->released_date,
            'released_by' => $this->released_by,
            'released_to' => $this->released_to,
            'sales_point' => $this->sales_point,
            'transferred_from' => $this->transferred_from,
            'transferred_to' => $this->transferred_to,
            'transferred_date' => $this->transferred_date,
            'transferred_by' => $this->transferred_by,
            'status' => $this->status,
            'branch' => $this->branch,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no]);

        return $dataProvider;
    }
}
