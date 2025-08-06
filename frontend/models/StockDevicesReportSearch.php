<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\StockDevicesReport;

/**
 * StockDevicesReportSearch represents the model behind the search form of `backend\models\StockDevicesReport`.
 */
class StockDevicesReportSearch extends StockDevicesReport
{
  public  $date_from;
  public  $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'status','location_from'], 'integer'],
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
        $query = StockDevicesReport::find();

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
            //'serial_no' => $this->serial_no,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'location_from' => $this->location_from,
        ]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere( [
                'or',
                [ '=', 'serial_no', $key ],
            ] );
        }

        return $dataProvider;
    }
}
