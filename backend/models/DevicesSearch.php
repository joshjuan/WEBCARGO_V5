<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Devices;

/**
 * DevicesSearch represents the model behind the search form of `backend\models\Devices`.
 */
class DevicesSearch extends Devices
{
    public $date_from;
    public $date_to;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'received_from', 'border_port', 'received_from_staff', 'created_by', 'status', 'branch', 'type', 'released_to','view_status'], 'integer'],
            [['serial_no', 'sim_card', 'remark', 'created_at'], 'safe'],
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
    public function searchIndex($params)
    {
        //   if (\Yii::$app->user->identity->user_type == User::PARTNER){
        $query = Devices::find();
        //  ->where(['branch' => \Yii::$app->user->identity->branch]);
        //->andWhere(['partiner'=>1]);

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
            //   $query->where('0=1');
            return $dataProvider;
        }

        if ($this->view_status==0){

           // print_r($this->status);die;
            $query->andFilterWhere([
               '!=', 'view_status' ,1,
            ]);
        }
        else{
            $query->andFilterWhere([
                'view_status' => $this->view_status,
            ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
           // 'view_status' => $this->view_status,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
        ]);
        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        return $dataProvider;

    }


    public function search($params)
    {

        $query = Devices::find();
        // ->where(['view_status' => Devices::registration]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

    public function searchAccount($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::accounts]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        // $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
        $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchAccountResults($params)
    {

        $query = Devices::find();

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            //  'view_status' => $this->view_status,
        ]);

        //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
        $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);


        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        $query->andFilterWhere([
            'view_status' => Devices::accounts
        ]);

        return $dataProvider;

    }


    public function searchAwaitingStorage($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::awaiting_store]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchAwaitingStorageResults($params)
    {

        $query = Devices::find();

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            //  'view_status' => $this->view_status,
        ]);

        //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
        $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);


        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        $query->andFilterWhere([
            'view_status' => Devices::awaiting_store
        ]);

        return $dataProvider;

    }

    public function searchStorageResults($params)
    {

        $query = Devices::find();

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            //  'view_status' => $this->view_status,
        ]);

        //¬  $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
        $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);


        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }

        $query->andFilterWhere([
            'view_status' => Devices::store
        ]);

        return $dataProvider;

    }

    public function searchStorage($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::store]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchAwaitingAllocation($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::awaiting_allocation]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchAllocation($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::allocation]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }


    public function searchAwaitReceive($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->andWhere(['view_status' => self::awaiting_receive])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        } else {
            $query = Devices::find()
                ->andWhere(['view_status' => self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function searchAwait($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::awaiting_receive])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::awaiting_receive])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function searchReceived($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->andWhere(['view_status' => self::received_devices])
                ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        } else {
            $query = Devices::find()
                ->andWhere(['view_status' => self::received_devices]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function searchReceive($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::received_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::received_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function searchIntransit($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::in_transit]);
        // ->andWhere(['status' => StockDevices::not_deactivated]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchDamaged($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::damaged]);
        // ->andWhere(['status' => StockDevices::not_deactivated]);

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchIntransitDevice($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::stock_devices])
                ->andFilterWhere(['status' => StockDevices::not_deactivated])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::stock_devices])
                ->andFilterWhere(['status' => StockDevices::not_deactivated])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function searchAvailable($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find()
                ->where(['view_status' => self::stock_devices])
                ->andWhere(['status' => StockDevices::available]);


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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        } else {
            $query = Devices::find()
                ->where(['branch' => \Yii::$app->user->identity->branch])
                ->andWhere(['view_status' => self::stock_devices])
                ->andWhere(['status' => StockDevices::available]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
                ->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark]);

            return $dataProvider;
        }
    }

    public function available($params)
    {

        $query = Devices::find();

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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
        foreach ($terms as $key) {
            $query->orFilterWhere([
                'or',
                ['=', 'serial_no', $key],
            ]);
        }
        $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);
        return $dataProvider;


    }

    public function released($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::released]);


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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function releasedSearch($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::released_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {

            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'released_to' => $this->released_to,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::released_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }


    public function searchRoad($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::in_transit])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::in_transit])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

    public function onRoad($params)
    {

        $query = Devices::find()
            ->where(['view_status' => self::on_road]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function onRoad1to7($params)
    {

        $date = date('Y-m-d');
        $endDate = date("Y-m-d", strtotime("$date -7 day"));


        $query = Devices::find()
            ->where(['view_status' => self::on_road])
            ->andWhere(['>', 'date(created_at)', $endDate]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function onRoad8to14($params)
    {

        $date = date('Y-m-d');
        $endDate = date("Y-m-d", strtotime("$date -8 day"));
        $startDate = date("Y-m-d", strtotime("$date -14 day"));


        $query = Devices::find()
            ->where(['view_status' => self::on_road])
            ->andWhere(['between', 'date(created_at)', $startDate, $endDate]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function onRoadAbove14($params)
    {

        $date = date('Y-m-d');
        $endDate = date("Y-m-d", strtotime("$date -14 day"));


        $query = Devices::find()
            ->where(['view_status' => self::on_road])
            ->andWhere(['<', 'date(created_at)', $endDate]);


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 100],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function searchBorderReturn($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::return_to_office]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

    public function searchBorderReceived($params)
    {

        $query = Devices::find()
            ->where(['view_status' => Devices::border_received]);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 300],
            'sort' => [
                'defaultOrder' => [
                    'created_at' => SORT_ASC,
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;
    }

    public function Fault($params)
    {
        $query = Devices::find()
            ->andWhere(['view_status' => self::fault_devices]);
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
            'received_from' => $this->received_from,
            'border_port' => $this->border_port,
            'received_from_staff' => $this->received_from_staff,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at,
            'status' => $this->status,
            'branch' => $this->branch,
            'type' => $this->type,
            'view_status' => $this->view_status,
        ]);

        $query->andFilterWhere(['like', 'serial_no', $this->serial_no])
            ->andFilterWhere(['like', 'sim_card', $this->sim_card])
            ->andFilterWhere(['like', 'remark', $this->remark]);

        return $dataProvider;

    }

    public function FaultSearch($params)
    {
        if (\Yii::$app->user->identity->user_type == User::PARTNER) {
            $query = Devices::find();
            // ->where(['branch' => \Yii::$app->user->identity->branch])
            //  ->andWhere(['view_status'=>self::awaiting_receive])
            //  ->andWhere(['partiner' => 1]);

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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,

                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);

            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::fault_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;

        } else {
            $query = Devices::find();
            //   ->where(['branch' => \Yii::$app->user->identity->branch]);
            //    ->andWhere(['view_status'=>self::awaiting_receive]);
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
                'received_from' => $this->received_from,
                'border_port' => $this->border_port,
                'received_from_staff' => $this->received_from_staff,
                'created_by' => $this->created_by,
                'created_at' => $this->created_at,
                'status' => $this->status,
                'branch' => $this->branch,
                'type' => $this->type,
                'view_status' => $this->view_status,
            ]);


            $terms = preg_split("/\\r\\n|\\r|\\n/", $this->serial_no);
            foreach ($terms as $key) {
                $query->orFilterWhere([
                    'or',
                    ['=', 'serial_no', $key],
                ]);
            }
            $query->andFilterWhere(['like', 'sim_card', $this->sim_card])
                ->andFilterWhere(['like', 'remark', $this->remark])
                ->andFilterWhere(['view_status' => self::fault_devices])
                ->andFilterWhere(['branch' => \Yii::$app->user->identity->branch]);
            return $dataProvider;
        }
    }

}
