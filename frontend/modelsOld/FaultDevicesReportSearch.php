<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\FaultDevicesReport;

/**
 * FaultDevicesReportSearch represents the model behind the search form of `frontend\models\FaultDevicesReport`.
 */
class FaultDevicesReportSearch extends FaultDevicesReport
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'created_by', 'status'], 'integer'],
            [['created_at', 'remarks','serial_no','date_from', 'date_to'], 'safe'],
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
        $query = FaultDevicesReport::find();

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
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
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


        $query->andFilterWhere(['like', 'remarks', $this->remarks])
           ->andFilterWhere(['branch'=>\Yii::$app->user->identity->branch])
           ->andFilterWhere(['between', 'DATE_FORMAT(created_at, "%Y-%m-%d")', $this->date_from, $this->date_to]);

        return $dataProvider;
    }
}
