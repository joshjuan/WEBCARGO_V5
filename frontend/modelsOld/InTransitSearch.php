<?php

namespace frontend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\InTransit;

/**
 * InTransitSearch represents the model behind the search form of `frontend\models\InTransit`.
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
            [['id', 'border', 'sales_person', 'type','branch','created_by', 'view_status'], 'integer'],
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
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $devices = Devices::find()
                ->select(['serial'])
                ->where(['partiner' => 1])
                ->asArray();


            $query = InTransit::find();
            //  $serial=Devices::find()->select(['serial'])->where(['!=','type',3])->asArray();
            $query->where(['in','serial_no',$devices])
                ->andWhere(['view_status' => Devices::in_transit])
                ->andWhere(['branch' => \Yii::$app->user->identity->branch]);

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
                'serial_no' => $this->serial_no,
                'branch' => $this->branch,
                'border' => $this->border,
                'type' => $this->type,
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
        else {


            $query = InTransit::find();
            //  $serial=Devices::find()->select(['serial'])->where(['!=','type',3])->asArray();
            $query->where(['view_status' => Devices::in_transit])
                ->andWhere(['branch' => \Yii::$app->user->identity->branch]);

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
                'serial_no' => $this->serial_no,
                'branch' => $this->branch,
                'border' => $this->border,
                'type' => $this->type,
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
    }

    public function searchClean($params)
    {
        $query = InTransit::find();


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
            'branch' => $this->branch,
            'border' => $this->border,
            'type' => $this->type,
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

    public function searchPending($params)
    {
        $query = InTransit::find();
        $device=Devices::find()->select(['serial'])->where(['type'=>2])->asArray();

       // $slave_device=Devices::find()->select(['serial'])->where(['type'=>3])->asArray();

        $sale_id=AwaitingReceive::find()->select(['sale_id'])
            ->where(['in','serial_no',$device])
            ->andWhere(['view_status'=>Devices::awaiting_receive])->asArray();

        $slaves=SalesTripSlaves::find()->select(['serial_no'])->where(['in','sale_id',$sale_id])->asArray();
        $query->where(['in','serial_no',$slaves])
            ->andWhere(['view_status'=>Devices::in_transit]);



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
              'serial_no' => $this->serial_no,
            'branch' => $this->branch,
            'border' => $this->border,
            'type' => $this->type,
            'sales_person' => $this->sales_person,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'view_status' =>Devices::in_transit,
        ]);

        $query->andFilterWhere(['like', 'tzl', $this->tzl])
            ->andFilterWhere(['like', 'vehicle_no', $this->vehicle_no])
            ->andFilterWhere(['like', 'container_number', $this->container_number]);


      //  $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
      //  $query->andFilterWhere(['in', 'serial_no', $terms]);


        return $dataProvider;
    }
}
