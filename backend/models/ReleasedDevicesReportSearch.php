<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ReleasedDevicesReport;

/**
 * ReleasedDevicesReportSearch represents the model behind the search form of `backend\models\ReleasedDevicesReport`.
 */
class ReleasedDevicesReportSearch extends ReleasedDevicesReport
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'released_by', 'released_to', 'transferred_from', 'transferred_to', 'status'], 'integer'],
            [['released_date', 'serial_no','transferred_date','date_from', 'date_to'], 'safe'],
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
        $query = ReleasedDevicesReport::find();

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
            'transferred_from' => $this->transferred_from,
            'transferred_to' => $this->transferred_to,
            'transferred_date' => $this->transferred_date,
            'status' => $this->status,
        ]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere( [
                'or',
                [ '=', 'serial_no', $key ],
            ] );
        }
        $query ->andFilterWhere(['between', 'DATE_FORMAT(received_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

        return $dataProvider;
    }
}
