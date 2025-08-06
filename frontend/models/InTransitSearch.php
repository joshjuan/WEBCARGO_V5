<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\InTransit;

/**
 * InTransitSearch represents the model behind the search form of `backend\models\InTransit`.
 */
class InTransitSearch extends InTransit
{
    public $date_from;
    public $date_to;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'border', 'sales_person', 'created_by', 'view_status'], 'integer'],
            [['tzl', 'created_at', 'vehicle_no', 'container_number','serial_no','date_from', 'date_to'], 'safe'],
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
        $query = InTransit::find()->where(['view_status'=>Devices::in_transit]);
        //$serial=Devices::find()->select('serial')->where(['in','type',[1,2]]);

        //$query->where(['in','serial_no',$serial]);

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
            'serial_no' => $this->serial_no,
            'border' => $this->border,
            'sales_person' => $this->sales_person,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'container_number', $this->container_number]);

        return $dataProvider;
    }

    public function searchClean($params)
    {
        $query = InTransit::find();


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
          //  'serial_no' => $this->serial_no,
            'border' => $this->border,
            'sales_person' => $this->sales_person,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'view_status' =>Devices::in_transit,
        ]);

        $query->andFilterWhere(['like', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'container_number', $this->container_number]);


        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        $query->andFilterWhere(['in', 'serial_no', $terms]);


        return $dataProvider;
    }
}
