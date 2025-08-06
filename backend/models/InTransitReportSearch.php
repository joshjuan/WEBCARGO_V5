<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InTransitReport;

/**
 * InTransitReportSearch represents the model behind the search form of `backend\models\InTransitReport`.
 */
class InTransitReportSearch extends InTransitReport
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'border', 'sales_person', 'created_by'], 'integer'],
            [['created_at', 'serial_no','vehicle_no', 'container_number','date_from', 'date_to'], 'safe'],
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
        $query = InTransitReport::find();
        $serial=Devices::find()->select(['serial_no'])->where(['!=','type',3])->asArray();
        $query->where(['in','serial_no',$serial]);

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
         //   'serial_no' => $this->serial_no,
            'border' => $this->border,
            'sales_person' => $this->sales_person,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
        ]);

        $terms = preg_split('/\r\n|\r|\n/', $this->serial_no);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere( [
                'or',
                [ '=', 'serial_no', $key ],
            ] );
        }

        $query->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'container_number', $this->container_number])
            ->andFilterWhere(['between', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

        return $dataProvider;
    }
}
